<?php

namespace App\Http\Controllers\API;

use App\Enums\RoleEnum;
use App\Exceptions\ExceptionHandler;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\AddExtraChargeRequest;
use App\Http\Requests\API\AssigningServicemenRequest;
use App\Http\Requests\API\CreateBookingRequest;
use App\Http\Requests\API\UpdateBookingRequest;
use App\Models\Booking;
use App\Repositories\API\BookingRepository;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public $repository;

    public function __construct(BookingRepository $repository)
    {
        $this->repository = $repository;
        $this->authorizeResource(Booking::class, 'booking', [
            'except' => ['show', 'store'],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $bookings = $this->repository->whereNotNull('parent_id');
            $bookings = $this->filter($bookings, $request);

            $bookings = $bookings->latest('created_at')->paginate($request->paginate);
            foreach ($bookings->items() as $booking) {
                if ($booking->parent) {
                    if ($booking->parent->sub_bookings->count() > 1) {
                        $booking->parent_booking_number = $booking->parent->booking_number;
                    } else {
                        $booking->booking_number = $booking->parent->booking_number;
                    }
                }
            }
    
            return $bookings;

        } catch (\Exception $e) {
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBookingRequest $request)
    {
        return $this->repository->createBooking($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->repository->show($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        return $this->repository->update($request->all(), $booking->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Booking $booking)
    {
        return $this->repository->destroy($booking->getId($request));
    }

    /**
     * Update Status the specified resource from storage.
     *
     * @param  int  $id
     * @param  int  $status
     * @return \Illuminate\Http\Response
     */
    public function status($id, $status)
    {
        return $this->repository->status($id, $status);
    }

    public function calculateCommission()
    {
        return $this->repository->calculateCommission();
    }

    public function filter($bookings, $request)
    {
        $roleName = Helpers::getCurrentRoleName();
        if ($roleName == RoleEnum::CONSUMER) {
            $bookings = $bookings->where('consumer_id', Helpers::getCurrentUserId());
        }

        if ($roleName == RoleEnum::PROVIDER) {
            $bookings = $this->repository->whereNotNull('parent_id')->where('provider_id', Helpers::getCurrentProviderId());
        }

        if ($roleName == RoleEnum::SERVICEMAN) {
            $servicemanId = Helpers::getCurrentUserId();
            $bookings = $bookings->whereNotNull('parent_id')
                ->whereHas('servicemen', function ($query) use ($servicemanId) {
                    $query->where('users.id', $servicemanId);
                });
        }

        if ($request->field && $request->sort) {
            $bookings = $bookings->orderBy($request->field, $request->sort);
        }

        if (isset($request->status)) {
            $booking_status_id = Helpers::getbookingStatusId($request->status);
            $bookings = $bookings->where('booking_status_id', $booking_status_id);
        }

        if ($request->start_date && $request->end_date) {
            $bookings = $bookings->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->category_ids) {
            $categoryIds = is_array($request->category_ids) ? $request->category_ids : [$request->category_ids];

            $bookings = $bookings->whereHas('service.categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            });
        }

        return $bookings;
    }

    public function rePayment(Request $request)
    {
        return $this->repository->rePayment($request);
    }

    public function payment(Request $request)
    {
        return $this->repository->payment($request);
    }

    public function verifyPayment(Request $request)
    {
        return $this->repository->verifyPayment($request);
    }

    public function assign(AssigningServicemenRequest $request)
    {
        return $this->repository->assign($request->all());
    }

    public function getInvoiceUrl(Request $request)
    {
        return $this->repository->getInvoiceUrl($request->booking_number);
    }

    public function getInvoice(Request $request)
    {
        return $this->repository->getInvoice($request);
    }

    public function addExtraCharges(AddExtraChargeRequest $request)
    {
        return $this->repository->addExtraCharges($request);
    }

    public function addserviceProofs(Request $request)
    {
        return $this->repository->addserviceProofs($request);
    }

    public function updateserviceProofs(Request $request)
    {
        return $this->repository->updateserviceProofs($request);
    }
}
