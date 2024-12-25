<?php

namespace Modules\Coupon\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Coupon\Entities\Coupon;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $model;

    public function __construct(Coupon $coupon)
    {
        $this->model = $coupon;
    }

    public function index(Request $request)
    {
        $coupon = $this->model->where('status', true);

        return $coupon->where('is_expired', 0)
        ->where(function ($query) {
            $query->whereNull('end_date')
                  ->orWhere('end_date', '>', Carbon::now());
        })
        ->latest('created_at')
        ->paginate($request->paginate ?? $coupon->count())
        ->pluck('is_expired', 'code');

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
