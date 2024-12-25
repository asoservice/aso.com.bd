<?php

namespace App\Repositories\Backend;

use App\Events\BookingReminderEvent;
use App\Exceptions\ExceptionHandler;
use App\Models\Booking;
use App\Models\Currency;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class BookingRepository extends BaseRepository
{
    protected $service;

    protected $setting;

    protected $currency;

    protected $user;

    public function boot()
    {
        try {
            $this->pushCriteria(app(RequestCriteria::class));

        } catch (\Exception $e) {
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function model()
    {
        $this->setting = new Setting();
        $this->service = new Service();
        $this->currency = new Currency();
        $this->user = new User();

        return Booking::class;
    }

    public function index($dataTable)
    {
        return $dataTable->render('backend.booking.index');
    }

    public function show($id)
    {
        $booking = $this->model->findOrFail($id);
        $settings = $this->setting->first();
        $default_currency = $this->currency->findOrFail($settings->values['general']['default_currency_id']);

        return view('backend.booking.show', [
            'booking' => $booking,
            'settings' => $settings->values,
            'currency' => $default_currency,
        ]);
    }

    public function getServicemen($id)
    {
        $booking = $this->model->findOrFail($id);
        $providerIds = [];
        foreach ($booking->services as $service) {
            $providerIds[] = $service->user_id;
        }
        $serviceman = $this->user->where('provider_id', $providerIds)->get();
        $serviceMenData = [];

        foreach ($serviceman as $serviceman) {
            $serviceMenData[] = [
                'id' => $serviceman->id,
                'name' => $serviceman->name,
            ];
        }

        return response()->json($serviceMenData);
    }

    public function showChild($id)
    {
        $childBooking = $this->model->findOrFail($id);
        $settings = $this->setting->first();
        $default_currency = $this->currency->findOrFail($settings->values['general']['default_currency_id']);

        return view('backend.booking.child', [
            'childBooking' => $childBooking,
            'settings' => $settings->values,
            'currency' => $default_currency,
        ]);
    }

    public function reminder()
    {
        try {

            $bookings = $this->model->whereNull('deleted_at')
                ->whereDate('date_time', Carbon::today());

            if ($bookings) {
                foreach ($bookings as $booking) {
                    event(new BookingReminderEvent($booking));
                }
            }

        } catch (Exception $e) {

            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }
}
