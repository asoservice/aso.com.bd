@use('app\Helpers\Helpers')
@extends('backend.layouts.master')
@section('title', __('static.payment_methods.payment_methods'))
@section('content')

<div class="row g-sm-4 g-3">
    @forelse ($paymentMethods as $paymentMethod)
    
        <div class="col-md-6">
            <div class="card tab2-card h-100">
                <div class="card-header">
                    <div class="header-img">
                        <img src="{{ $paymentMethod['image'] }}" alt="" class="img-fluid">
                        <h5>{{$paymentMethod['name']}}</h5>
                    </div>
                    <div class="status-div">
                        <label for="{{$paymentMethod['name']}}">{{__('static.booking.status')}}:</label>
                        <div class="editor-space">
                            <label class="switch">
                                <input class="form-check-input" type="checkbox" name="status" id="" value="1" @checked($paymentMethod['status']) onchange="paymentStatus('{{ $paymentMethod['slug'] }}', this.checked)">
                                <span class="switch-state"></span>
                            </label>
                        </div>
                    </div>
                </div>
    
                <div class="card-body">
                    <form action="{{route('backend.paymentmethods.update', $paymentMethod['slug'])}}" id="" method="POST">
                        @csrf
                        @method("POST")
                        @foreach ($paymentMethod['fields'] as $fieldKey => $field)
                        @php
                        $fieldValue = env(strtoupper($fieldKey));
                        @endphp
                            <div class="form-group row">
                                <label class="col-xxl-4" for="{{ $fieldKey }}">{{ $field['label'] }}</label>
                                <div class="col-xxl-8">
                                    @if ($field['type'] === 'select')
                                        <select class="form-control select-2" name="{{ $fieldKey }}" id="{{ $fieldKey }}" data-placeholder="{{ $field['label'] }}">
                                                <option class="select-placeholder" value=""></option>
                                            @foreach ($field['options'] as $optionValue => $optionLabel)
                                                <option value="{{ $optionValue }}" @if(!is_null($fieldValue)) @selected($optionValue==$fieldValue) @endif>{{ $optionLabel }}</option>
                                            @endforeach
                                        </select>
                                    @elseif ($field['type'] === 'textarea')
                                        <textarea class="form-control" name="{{ $fieldKey }}" id="{{ $fieldKey }}" placeholder="{{ $field['label'] }}"></textarea>
                                    @elseif ($field['type'] === 'password')
                                        <input class="form-control" type="password" name="{{ $fieldKey }}" id="{{ $fieldKey }}" placeholder="{{ $field['label'] }}" value="{{  Helpers::encryptKey($fieldValue) }}">
                                    @elseif ($field['type'] === 'text')
                                        <input class="form-control" type="text" name="{{ $fieldKey }}" id="{{ $fieldKey }}" placeholder="{{ $field['label'] }}" value="{{ $paymentMethod['title']  }}">
                                    @else
                                        <input class="form-control" type="{{ $field['type'] }}" name="{{ $fieldKey }}" id="{{ $fieldKey }}" value="{{  Helpers::encryptKey($fieldValue) }}" placeholder="{{ $field['label'] }}">
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <div class="footer">
                            <button id="submitBtn" type="submit" class="btn btn-primary spinner-btn">{{ __('static.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @empty
    <div>
        <h4>Payment method not found</h4>
    </div>
    @endforelse
</div>
@endsection

@push('js')
<script>
    (function() {
        "use strict";

        function paymentStatus(slug, status) {
            fetch(`{{ url('/backend/payment-methods/status') }}/${slug}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: status ? 1 : 0
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.error);
                    }
                })
                .catch(error => {
                    toastr.error(error.message || "An error occurred");
                });
        }

        window.paymentStatus = paymentStatus;
    })();
</script>

@endpush
