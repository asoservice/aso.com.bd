@extends('backend.layouts.master')
@section('title', __('static.notification.send_notifications'))
@section('content')
    <div class="row">
        <div class="m-auto col-12-8">
            <div class="card tab2-card">
                <div class="card-header">
                    <h5>{{ __('static.notification.send_notifications') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4 align-items-center">
                        <div class="col-xxl-7 col-xl-8">
                            <form action="{{ route('backend.send-notification') }}" id="sendNotificationForm" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-md-2" for="zone">{{ __('static.notification.zone') }}</label>
                                    <div class="col-md-10 error-div select-dropdown">
                                        <select class="select-2 form-control" id="zone" name="zone"
                                            data-placeholder="{{ __('static.notification.select_zone') }}">
                                            <option class="select-placeholder" value=""></option>
                                            @foreach ($zones as $key => $option)
                                                <option class="option" value="{{ $key }}"
                                                    @if (old('zone') == $key) selected @endif>{{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('service_type')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="notification_send_to">{{ __('static.notification.notification_send_to') }}<span>
                                            *</span></label>
                                    <div class="col-md-10 error-div select-dropdown">
                                        <select class="select-2 form-control" id="notification_send_to"
                                            name="notification_send_to"
                                            data-placeholder="{{ __('static.notification.select_notification_send_to') }}">
                                            <option class="select-placeholder" value=""></option>
                                            @foreach (['user' => 'User', 'provider' => 'Provider'] as $key => $option)
                                                <option class="option" value="{{ $key }}"
                                                    @if (old('notification_send_to') == $key) selected @endif>{{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('service_type')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row select-send-to">
                                    <label class="col-md-2" for="send_to">{{ __('static.notification.send_to') }}</label>
                                    <div class="col-md-10 error-div select-dropdown">
                                        <select class="select-2 form-control" id="send_to" name="send_to"
                                            data-placeholder="{{ __('static.notification.select_send_to') }}">
                                            <option class="select-placeholder" value=""></option>
                                            @foreach (['all' => 'All', 'service' => 'Service'] as $key => $option)
                                                <option class="option" value="{{ $key }}"
                                                    @if (old('send_to') == $key) selected @endif>{{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row select-services" style="display: none">
                                    <label class="col-md-2"
                                        for="service_id">{{ __('static.notification.services') }}</label>
                                    <div class="col-md-10 error-div select-dropdown">
                                        <select class="select-2 form-control user-dropdown" id="service_id" name="service_id"
                                            data-placeholder="{{ __('static.notification.select_service') }}">
                                            <option value=""></option>
                                            @foreach ($services as $key => $option)
                                                <option value="{{ $option->id }}"
                                                    image="{{ $option->getFirstMedia('image')?->getUrl() }}"
                                                    @if (old('service_id') == $option->id) selected @endif>{{ $option->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2" for="">{{ __('static.notification.title') }}<span>
                                            *</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" id="title" name="title"
                                            value="{{ old('title') }}"
                                            placeholder="{{ __('static.notification.enter_title') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2" for="description">{{ __('static.description') }}</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" placeholder="{{ __('static.enter_description') }}" rows="4" id="description"
                                            name="description" cols="50">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2" for="name">{{ __('static.notification.image') }}</label>
                                    <div class="col-md-10">
                                        <input class="form-control" id="image" type="file" accept=".jpg, .png, .jpeg"
                                            name="image" value="image">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2" for="name">{{ __('static.notification.url') }}</label>
                                    <div class="col-md-10">
                                        <input class="form-control" id="url" type="url"
                                            placeholder="{{ __('static.notification.enter_url') }}" name="url"
                                            value="{{ old('url') }}">
                                    </div>
                                </div>
                                <div class="footer">
                                    <button type="submit"
                                        class="btn btn-primary spinner-btn">{{ __('static.submit') }}</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-xxl-5 col-xl-4 text-center">
                             <div class="notification-mobile-box">
                                <div class="notify-main">
                                    <img src="{{ asset('admin/images/notify-old.png') }}" class="notify-img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            "use strict";

            $("#sendNotificationForm").validate({
                ignore: [],
                rules: {
                    "notification_send_to": "required",
                    "send_to": {
                        required: isNotificationSendTo
                    },
                    "service_id": {
                        required: isSendTo
                    },
                    "image": {
                        accept: "image/jpeg, image/png"
                    },
                    "title": "required",
                    "discription": "required"
                },
                messages: {
                    "image": {
                        accept: "Only JPEG and PNG files are allowed.",
                    },
                }
            });

            function isSendTo(element) {
                return $('select[name="send_to"]').val() == "service";
            }

            function isNotificationSendTo(element) {
                return $('select[name="notification_send_to"]').val() == "user";
            }

            $('select[name="notification_send_to"]').on('change', function() {
                var notification_send_to = $(this).val();
                if (notification_send_to === 'provider') {
                    $(".select-send-to").hide();
                } else {
                    $(".select-send-to").show();
                }
            });

            $('select[name="send_to"]').on('change', function() {
                var sendTo = $(this).val();
                if (sendTo === 'all') {
                    $(".select-services").hide();
                } else {
                    $(".select-services").show();
                }
            });
        });
    </script>
@endpush
