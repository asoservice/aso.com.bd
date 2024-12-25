<div class="roles">
    <div class="card-header">
        <h5>{{ isset($role) ? __('static.roles.edit') : __('static.roles.create') }}</h5>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-2" for="name">{{ __('static.name') }}<span> *</span></label>
            <div class="col-md-10">
                <input class='form-control' type="text" name="name" id="name"
                    value="{{ isset($role->name) ? $role->name : old('name') }}"
                    placeholder="{{ __('static.roles.enter_name') }}">
                @error('name')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
</div>
<div class="permission">
    <div class="card-header">
        <div class="form-group row">
            <label class="col-md-2 m-0" for="name">{{ __('static.roles.permissions') }}<span> *</span></label>
            <div class="col-md-10">
                @error('permissions')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="permission-section">
            @foreach ($modules as $key => $module)
                <ul>
                    <li>
                        <h5 class="text-truncate">
                            <span data-bs-toggle="tooltip" data-placement="bottom"
                            title="{{ ucwords(str_replace('_', ' ', $module->name)) }}">{{ ucwords(str_replace('_', ' ', $module->name)) }}:</span>
                        </h5>
                    </li>

                    @php
                        $permissions = isset($role) ? $role->getAllPermissions()->pluck('name')->toArray() : [];
                        $isAllSelected = count(array_diff(array_values($module->actions), $permissions)) === 0;
                    @endphp
                    <li>
                        <div class="form-group m-checkbox-inline mb-0 d-flex">
                            <label class="d-block" for="all{{ $module->name }}">

                                <input type="checkbox"
                                    class="checkbox_animated select-all-permission select-all-for-{{ $module->name }}"
                                    id="all-{{ $module->name }}" value="{{ $module->name }}"
                                    {{ $isAllSelected ? 'checked' : '' }}>{{ __('All') }}

                            </label>
                        </div>
                    </li>
                    @foreach ($module->actions as $action => $permission)
                        <li>
                            <label class="d-block" for="{{ $permission }}" data-action="{{ $action }}"
                                data-module="{{ $module->name }}">
                                <input type="checkbox" name="permissions[]"
                                    class="checkbox_animated module_{{ $module->name }} module_{{ $module->name }}_{{ $action }}"
                                    value="{{ $permission }}" id="{{ $permission }}"
                                    {{ in_array($permission, $permissions) ? 'checked' : '' }}>{{ $action }}
                            </label>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>
    </div>
</div>
@push('js')
    <script>
        $(document).ready(function() {
            'use strict';
            $(document).on('click', '.select-all-permission', function() {
                let value = $(this).prop('value');
                $('.module_' + value).prop('checked', $(this).prop('checked'));
            });
            $('.checkbox_animated').not('.select-all-permission').on('change', function() {
                let $this = $(this);
                let $label = $this.closest('label');
                let module = $label.data('module');
                let action = $label.data('action');
                let total_permissions = $('.module_' + module).length;
                let $selectAllCheckBox = $this.closest('.' + module + '-permission-list').find(
                    '.select-all-permission');
                let total_checked = $('.module_' + module).filter(':checked').length;
                let isAllChecked = total_checked === total_permissions;
                if ($this.prop('checked')) {
                    $('.module_' + module + '_index').prop('checked', true);

                } else {
                    let moduleCheckboxes = $(`input[type="checkbox"][data-module="${module}"]:checked`);
                    if (moduleCheckboxes.length === 0) {
                        if (action === 'index') {
                            $('.module_' + module).prop('checked', false);
                        }
                        $(`.module_${module}_${action}`).prop('checked', false);
                        $('.select-all-for-' + module).prop('checked', false);
                    }
                }

                $('.select-all-for-' + module).prop('checked', isAllChecked);
            });

            $('#roleForm').validate({});
        });


        $("#role-form").validate({
            ignore: [],
            rules: {
                "name": {
                    required: true
                },
            }
        });

        $('#submitBtn').on('click', function(e) {
            $("#role-form").valid();
        });
        
            $('[data-bs-toggle="tooltip"]').tooltip();
        
    </script>
@endpush
