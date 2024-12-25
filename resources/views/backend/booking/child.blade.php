@extends('backend.layouts.master')
@section('title', __('static.booking.details'))
@section('content')
@use('app\Helpers\Helpers')
<div class="tab2-card">
    <ul class="nav nav-tabs" id="bookingTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="v-pills-tabContent" data-bs-toggle="pill" data-bs-target="#booking_details" type="button" role="tab" aria-controls="booking_details" aria-selected="true">
                <i data-feather="settings"></i>
                {{ __('static.booking.general') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#booking_status" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false"><i data-feather="alert-circle"></i>
                {{ __('static.booking.status') }}
            </a>
        </li>
    </ul>
    <div class="tab-content" id="bookingTabContent">
        <div class="tab-pane fade show active" id="booking_details" role="tabpanel" aria-labelledby="booking_details" tabindex="0">
            <form route="backend.update.settings" method="PUT" enctype="multipart/form-data" class="needs-validation user-add">
                <div class="service-main row g-4">
                    <div class="col-xxl-8 col-12">
                        <div class="booking-detail card summary-detail">
                            <div class="card-header d-flex align-items-center gap-2 justify-content-between px-0 pt-0">
                                <div>
                                    <h5>{{ __('static.booking.details') }} #{{ $childBooking->booking_number }}</h5>
                                    <span>{{ __('static.booking.created_at') }}{{ $childBooking->created_at->format('j F Y, g:i A') }}</span>
                                </div>
                                @if ($childBooking->servicemen == null)
                                    <button data-bs-toggle="modal" data-bs-target="#assignmodal" id="assign_serviceman">{{ __('static.booking.assign') }}</button>
                                @endif
                            </div>
                            <div class="booking-header mt-3">
                                <h4>{{__('static.provider_details')}}</h4>
                            </div>
                            <ul>
                                <li>
                                    <p>{{__('static.name')}}:</p>
                                    <span>{{ $childBooking->provider?->name }}</span>
                                </li>
                                <li>
                                    <p>{{__('static.email')}}:</p>
                                    <span>{{ $childBooking->provider?->email }}</span>
                                </li>
                                @if (isset($childBooking->provider?->code) && isset($childBooking->provider->phone))
                                <li>
                                    <p>{{__('static.phone')}}:</p>
                                    <span>+{{ $childBooking->provider?->code . ' ' . $childBooking->provider->phone }}</span>
                                </li>
                                @endif
                                @if (isset($childBooking->provider->getPrimaryAddressAttribute()->country->name))
                                <li>
                                    <p>{{__('static.country')}}:</p>
                                    <span>{{ $childBooking->provider->getPrimaryAddressAttribute()->country->name }}</span>
                                </li>
                                @endif
                                @if (isset($childBooking->provider->getPrimaryAddressAttribute()->state->name))
                                <li>
                                    <p>{{__('static.state')}}:</p>
                                    <span>{{ $childBooking->provider->getPrimaryAddressAttribute()->state->name }}</span>
                                </li>
                                @endif
                                @if (isset($childBooking->provider->getPrimaryAddressAttribute()->city))
                                <li>
                                    <p>{{__('static.city')}}:</p>
                                    <span>{{ $childBooking->provider->getPrimaryAddressAttribute()->city }}</span>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-12">
                        <div class="row g-4">
                            <div class="col-xxl-12 col-xl-6 col-12">
                                <div class="booking-detail-2 card border-0 shadow-sm">
                                    <div class="card-header theme-bg-color text-white">
                                        <h4 class="mb-0">{{__('static.consumer_details')}}</h4>
                                    </div>
                                    
                                    <div class="provider-details-box">
                                        <ul class="list-unstyled mb-0">
                                            <li>
                                                <p><span>{{__('static.booking.consumer_name')}} :</span> {{ $childBooking->consumer->name }}</p>
                                            </li>
                                            <li>
                                                <p><span>{{__('static.phone')}} :</span> +{{ $childBooking->consumer->code.' '.$childBooking->consumer->phone }}</p>
                                            </li>
                                            <li>
                                                <p><span>{{__('static.country')}}:</span> {{ $childBooking->consumer->getPrimaryAddressAttribute()->country->name }}</p>
                                            </li>
                                            <li>
                                                <p><span>{{__('static.state')}}:</span> {{ $childBooking->consumer->getPrimaryAddressAttribute()->state->name }}</p>
                                            </li>
                                            <li>
                                                <p><span>{{__('static.city')}}:</span> {{ $childBooking->consumer->getPrimaryAddressAttribute()->city }}</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-12 col-xl-6 col-12">
                                <div class="booking-detail-2 card border-0 shadow-sm">
                                    <div class="card-header theme-bg-color text-white">
                                        <h4>{{ __('static.summary') }}</h4>
                                        <div class="btn-popup ms-auto mb-0">
                                            <a href="{{ route('invoice', $childBooking->booking_number)}}" class="btn link-btn">{{ __('static.booking.invoice') }}</a>
                                        </div>
                                    </div>
                                    <div class="provider-details-box">
                                        <ul class="list-unstyled mb-0">
                                            <li>
                                                <p><span>{{__('static.booking.payment_method')}}:</span> {{ $childBooking->payment_method }}</p>
                                            </li>
                                            <li>
                                                <p><span>{{__('static.booking.payment_status')}}:</span> {{ $childBooking->payment_status }}</p>
                                            </li>
                                            <li>
                                                <p><span>{{__('static.booking.coupon_discount')}}:</span> {{Helpers::getSettings()['general']['default_currency']->symbol}} {{ $childBooking->coupon_total_discount }}</p>
                                            </li>
                                            <li>
                                                <p><span>{{__('static.booking.service_discount')}}:</span> {{Helpers::getSettings()['general']['default_currency']->symbol}} {{ $childBooking->discount ?? 0 }}</p>
                                            </li>
                                            <li>
                                                <p><span>{{__('static.booking.service_tax')}}:</span> {{Helpers::getSettings()['general']['default_currency']->symbol}} {{ $childBooking->tax ?? 0 }}</p>
                                            </li>
                                            <li >
                                                <p><span>{{__('static.booking.service_amount')}}:</span> {{Helpers::getSettings()['general']['default_currency']->symbol}} {{ $childBooking->service_price ?? 0 }}</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="tab-pane fade" id="booking_status">
            <div class="row booking-status g-4">
                <div class="col-xxl-4 col-xl-5">
                    <div class="booking-log card">
                        <div class="card-header d-flex align-items-center gap-2 justify-content-between px-0 pt-0">
                            <div>
                                <h5>{{ __('static.booking.details') }} #{{ $childBooking->booking_number }}</h5>
                                <span>{{ __('static.booking.created_at') }}{{ $childBooking->created_at->format('j F Y, g:i A') }}</span>
                            </div>
                            @if ($childBooking->servicemen == null)
                                <div class="btn-popup ms-auto mb-0">
                                    <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignmodal">{{ __('static.booking.assign') }}</button>
                                </div>
                            @endif
                        </div>
                        <div class="card-body px-2 status-body custom-scroll">
                            <ul>
                                @forelse ($childBooking->booking_status_logs as $status)
                                <li class="d-flex">
                                    <div class="activity-dot activity-dot-{{ $status->status->hexa_code }}">
                                        {{ $status->status->hexa_code }}
                                    </div>
                                    <div class="w-100 ms-3">
                                        <p class="d-flex justify-content-between mb-1"><span class="date-content">{{ $status->created_at->format('d-m-Y') }},</span><span>{{ $status->created_at->format('g:i A') }}</span></p>
                                        <h6>{{ $status->status->name }}<span class="dot-notification"></span></h6>
                                        <p class="f-light">{{ $status->description }}</p>
                                    </div>
                                </li>
                                @empty
                                <li class="d-flex">
                                    <div id="activity-dot-not-found" class="activity-dot activity-dot-primary"></div>
                                    <div class="w-100 ms-3">
                                        <h4 class="no-status">{{__('static.no_status_log_found')}}</h4>
                                    </div>
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                @if($childBooking->servicemen->count() > 0)
                <div class="col-xxl-8 col-xl-7">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h5>{{ __('static.servicemen_information') }}</h5>
                        </div>
                        <div class="card-body common-table">
                            <div class="serviceman-info-table">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{__('static.name')}}</th>
                                                <th>{{__('static.email')}}</th>
                                                <th>{{__('static.phone')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($childBooking->servicemen as $serviceman)
                                            <tr>
                                                <td>{{ $serviceman->name }}</td>
                                                <td>{{ $serviceman->email }}</td>
                                                <td>+{{ $serviceman->code . ' ' . $serviceman->phone }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="assignmodal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content service-man">
            <div class="modal-header">
                <h5>{{__('static.assign_service')}}</h5>
            </div>
            <div class="modal-body text-start">
                <div class="service-man-detail">
                    <div class="form-group row">
                        <label class="col-md-2" for="servicemen">{{ __('static.booking.serviceman') }}</label>
                        <div class="col-md-10 error-div select-dropdown">
                            <select class="select-2 form-control" id="servicemen" search="true" name="servicemen" data-placeholder="{{ __('static.booking.select_serviceman') }}">
                                <option class=""></option>
                            </select>
                            @error('servicemen')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('backend.booking.assign') }}" class="assign-btn">
                    {{ __('static.save') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('admin/js/vectormap.min.js') }}"></script>
<script src="{{ asset('admin/js/vectormap.js') }}"></script>
<script src="{{ asset('admin/js/vectormapcustom.js') }}"></script>
<script>
    (function($) {
        "use strict";

        $(document).ready(function() {
            // Click event handler for assign_serviceman button
            $("#assign_serviceman").click(function() {
                var booking_id = "{{$childBooking->id}}";
                $.ajax({
                    url: "{{ route('backend.booking.getServicemen') }}",
                    type: "GET",
                    data: {
                        booking_id: booking_id,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function(data) {
                        $("#servicemen").empty();
                        $.each(data, function(id, name) {
                            $("#servicemen").append(
                                $("<option></option>").val(id).text(name)
                            );
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });

            // Loop to remove '#' from class names
            var elements = document.getElementsByClassName('activity-dot');
            for (var i = 0; i < elements.length; i++) {
                var element = elements[i];
                var className = element.className;
                className = className.replace('#', '');
                element.className = className;
            }
        });
    })(jQuery);
</script>
@endpush
