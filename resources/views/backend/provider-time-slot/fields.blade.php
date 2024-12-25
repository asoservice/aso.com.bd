
<div class="form-group row">
    <label class="col-md-2" for="provider_id">{{ __('static.provider_time_slot.provider') }}<span> *</span></label>
    <div class="col-md-10 error-div select-dropdown">
        <select class="select-2 form-control user-dropdown" name="provider_id"
            data-placeholder="{{ __('static.provider_time_slot.select_provider') }}" required>
            <option class="select-placeholder" value=""></option>
            @foreach ($providers as $key => $option)
                <option value="{{ $option?->id }}" sub-title="{{ $option?->email }}"
                    image="{{ $option->getFirstMedia('image')?->getUrl() }}"
                    {{ old('option_id', isset($timeSlot) ? $timeSlot?->option?->id : '') == $key ? 'selected' : '' }}>
                    {{ $option->name }}
                </option>
               
            @endforeach
        </select>
        @error('provider_id')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-md-2" for="interval">{{ __('static.provider_time_slot.interval') }}<span> *</span></label>
    <div class="col-md-10">
        <input type="number" min="1" name="gap"
            placeholder="{{ __('static.provider_time_slot.enter_interval') }}" class="form-control"
            value="{{ $timeSlot->gap ?? old('gap') }}" required>
        @error('gap')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-md-2" for="time_unit">{{ __('static.provider_time_slot.time_unit') }}<span> *</span></label>
    <div class="col-md-10 error-div select-dropdown">
        <select class="select-2 form-control" id="time_unit" name="time_unit"
            data-placeholder="{{ __('static.provider_time_slot.select_interval_time_in') }}" required>
            <option value=""></option>
            @foreach (['hours' => 'Hours', 'minutes' => 'Minutes'] as $key => $option)
                <option class="option" value="{{ $key }}" @if (old('time_unit', isset($timeSlot) ? $timeSlot->time_unit : '') == $key) selected @endif>
                    {{ $option }}
                </option>
            @endforeach
        </select>
        @error('time_unit')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
@if (isset($timeSlot))
    <div class="time-container mb-2">
        @foreach ($timeSlot->time_slots as $index => $slot)
            <div class="time-slot mb-4">
                <div class="form-group row g-3">
                    <label class="col-md-2">{{ __('static.provider_time_slot.start_time') }}<span> *</span></label>
                    <div class="col-md-8 col-sm-10">
                        <div class="row g-4 time-slots-structure">
                            <div class="col-sm-6 col-12">
                                <div class="form-group row start-time_{{ $index }}">
                                    <label class="col-12"
                                        for="start_time_{{ $index }}">{{ __('static.provider_time_slot.start_time') }}<span>
                                            *</span></label>
                                    <div class="col-12">
                                        <input id="picker" type="time"
                                            name="time_slots[{{ $index }}][start_time]" class="form-control"
                                            placeholder="{{ __('static.provider_time_slot.select_start_time') }}"
                                            value="{{ old('time_slots.' . $index . '.start_time', date('H:i', strtotime($slot['start_time']))) }}"
                                            required>
                                        @error('time_slots.' . $index . '.start_time')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="form-group row end-time">
                                    <label class="col-12"
                                        for="end_time_{{ $index }}">{{ __('static.provider_time_slot.end_time') }}<span>
                                            *</span></label>
                                    <div class="col-12">
                                        <input id="picker" type="time"
                                            name="time_slots[{{ $index }}][end_time]" class="form-control"
                                            placeholder="{{ __('static.provider_time_slot.select_end_time') }}"
                                            value="{{ old('time_slots.' . $index . '.end_time', date('H:i', strtotime($slot['end_time']))) }}"
                                            required>
                                        @error('end_time')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="form-group row">
                                    <label class="col-12"
                                        for="day_0">{{ __('static.provider_time_slot.day_week') }}<span>
                                            *</span></label>
                                    <div class="col-12 error-div select-dropdown">
                                        <select class="select-2 form-control"
                                            name="time_slots[{{ $index }}][day]"
                                            data-placeholder="{{ __('static.provider_time_slot.select_day_week') }}"
                                            required>
                                            <option class="select-placeholder" value=""></option>
                                            @foreach (['MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY'] as $day)
                                                <option class="option" value="{{ $day }}"
                                                    @if (old('time_slots.' . $index . '.day', $slot['day']) == $day) selected @endif>
                                                    {{ $day }}</option>
                                            @endforeach
                                        </select>
                                        @error('day')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="form-group row">
                                    <label class="col-12" for="status">{{ __('static.status') }}<span>
                                            *</span></label>
                                    <div class="col-12 error-div select-dropdown">
                                        <select class="select-2 form-control"
                                            name="time_slots[{{ $index }}][status]"
                                            data-placeholder="{{ __('Select Status') }}" required
                                            @if (isset($provider)) disabled @endif>
                                            <option value=""></option>
                                            @foreach (['0' => 'Active', '1' => 'Deactive'] as $statusKey => $statusOption)
                                                <option class="option" value="{{ $statusKey }}"
                                                    @if (old('time_slots.' . $index . '.status', $slot['status']) == $statusKey) selected @endif>
                                                    {{ $statusOption }}</option>
                                            @endforeach
                                        </select>
                                        @error('time_slots.' . $index . '.status')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-2 text-sm-end text-start">
                        <div class="form-group row">
                            <label class="col-12"></label>
                            <div class="col-12">
                                <div class="remove-time-slot">
                                    <i data-feather="trash-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="time-container mb-2">
        <div class="time-slot mb-4">
            <div class="form-group row g-3">
                <label class="col-md-2">Select Slot<span> *</span></label>
                <div class="col-md-8 col-sm-10">
                    <div class="row g-4 time-slots-structure">
                        <div class="col-sm-6 col-12">
                            <div class="form-group row start-time">
                                <label class="col-12"
                                    for="start_time_0">{{ __('static.provider_time_slot.start_time') }}<span>
                                        *</span></label>
                                <div class="col-12">
                                    <input placeholder="{{ __('static.provider_time_slot.select_start_time') }}"
                                        type="time" name="time_slots[0][start_time]" class="form-control"
                                        value="{{ old('time_slots.0.start_time') }}" required> {{-- <div class="position-relative">
                                        <input id="time-picker"  placeholder="Select start time" type="time" name="time_slots[0][start_time]" class="form-control" value="{{  old('time_slots') }}" required>
                                <i data-feather="clock"></i>
                            </div> --}}
                                    @error('start_time')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="form-group row end-time">
                                <label class="col-12"
                                    for="end_time_0">{{ __('static.provider_time_slot.end_time') }}<span>
                                        *</span></label>
                                <div class="col-12">
                                    <input placeholder="{{ __('static.provider_time_slot.select_end_time') }}"
                                        type="time" name="time_slots[0][end_time]" class="form-control"
                                        value="{{ old('time_slots.0.end_time') }}" required> {{-- <div class="position-relative">
                                        <input id="time-picker1"  placeholder="Select end time" type="time" name="time_slots[0][end_time]" class="form-control" value="{{ old('end_time') }}" required>
                            <i data-feather="clock"></i>
                        </div> --}}
                                    @error('end_time')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="form-group row">
                                <label class="col-12"
                                    for="day_0">{{ __('static.provider_time_slot.day_week') }}<span>
                                        *</span></label>
                                <div class="col-12 error-div select-dropdown">
                                    <select class="select-2 form-control" name="time_slots[0][day]"
                                        data-placeholder="{{ __('static.provider_time_slot.select_day_week') }}"
                                        required>
                                        <option class="select-placeholder" value=""></option>
                                        @foreach (['MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY'] as $day)
                                            <option class="option" value="{{ $day }}">{{ $day }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('day')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="form-group row">
                                <label class="col-12" for="status">{{ __('static.status') }}<span> *</span></label>
                                <div class="col-12 error-div select-dropdown">
                                    <select class="select-2 form-control" name="time_slots[0][status]"
                                        data-placeholder="{{ __('static.select_status') }}" required
                                        @if (isset($provider)) disabled @endif>
                                        <option value=""></option>
                                        @foreach (['0' => 'Active', '1' => 'Deactive'] as $key => $option)
                                            <option class="option" value="{{ $key }}"
                                                @if (old('type', isset($provider) ? $provider->type : '') == $key) selected @endif>{{ $option }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1 col-sm-2 text-sm-end text-start">
                    <div class="form-group row">
                        <label class="col-12"></label>
                        <div class="col-12">
                            <div class="remove-time-slot">
                                <i data-feather="trash-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<div class="form-group row mt-4">
    <label class="col-md-2" for="add-time-slot"></label>
    <div class="col-md-10">
        <button type="button" id="add-time-slot"
            class="btn btn-secondary add-time-slot">{{ __('static.provider_time_slot.add_time_slot') }}</button>
    </div>
</div>
@push('js')
    <script>
        (function($) {
            "use strict";

            $(document).ready(function() {
                $('#providerTimeSlotForm').validate();

                // Add Time Slot
                $('#add-time-slot').unbind().click(function() {
                    var allInputsFilled = true;

                    // Check if any previous input is empty
                    $('.time-slot').find('.form-group.row').each(function() {
                        var startTime = $(this).find(
                            'input[name^="time_slots"][name$="[start_time]"]').val()?.trim();
                        var endTime = $(this).find(
                            'input[name^="time_slots"][name$="[end_time]"]').val()?.trim();
                        var day = $(this).find('select[name^="time_slots"][name$="[day]"]')
                        .val()?.trim();

                        if (startTime === '' || endTime === '' || day === '') {
                            allInputsFilled = false;
                            $(this).find(
                                    'input[name^="time_slots"], select[name^="time_slots"]')
                                .addClass('is-invalid');
                            $(this).find(
                                    'input[name^="time_slots"], select[name^="time_slots"]')
                                .removeClass('is-valid');
                        } else {
                            $(this).find(
                                    'input[name^="time_slots"], select[name^="time_slots"]')
                                .removeClass('is-invalid');
                        }
                    });

                    if (!allInputsFilled) {
                        return;
                    }

                    $(".select-2").select2("destroy");

                    
                    var inputGroup = $('.time-slot').last().clone();
                    var newIndex = $('.time-slot').length;
                    inputGroup.find('input[name^="time_slots"]').each(function() {
                        var oldName = $(this).attr('name');
                        var newName = oldName.replace(/\[\d+\]/, '[' + newIndex + ']');
                        $(this).attr('name', newName).val('');
                    });
                    inputGroup.find('select[name^="time_slots"]').each(function() {
                        var oldName = $(this).attr('name');
                        var newName = oldName.replace(/\[\d+\]/, '[' + newIndex + ']');
                        $(this).attr('name', newName).val('');
                    });

                    $(".time-container").append(inputGroup);
                    $('.select-2').select2();
                });

                $(document).on('click', '.remove-time-slot', function() {
                    $(this).closest('.time-slot').remove();
                });

                $(document).on('change', 'input[name^="time_slots"][name$="[end_time]"]', function() {
                    var timeSlotRow = $(this).closest('.time-slots-structure');
                    var startTimeInput = timeSlotRow.find('input[name$="[start_time]"]');
                    var endTimeInput = timeSlotRow.find('input[name$="[end_time]"]');

                    var startTime = startTimeInput.val();
                    var endTime = endTimeInput.val();
                    if (startTime === '') {
                        alert("{{ __('static.provider_time_slot.select_start_time_first') }}");
                        endTimeInput.val("");
                    } else if (startTime !== '' && endTime !==
                        '') {
                        if (endTime <= startTime) {
                            alert("{{ __('static.provider_time_slot.end_time_must_be_start_time') }}");
                            endTimeInput.val("");
                        }
                    }
                });

            });
        })(jQuery);
    </script>
@endpush
