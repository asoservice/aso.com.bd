@extends('backend.layouts.master')

@section('title', __('static.users.users'))

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5>{{ __('static.users.system_users') }}</h5>
            <div class="btn-action">
                @can('backend.user.create')
                    <div class="btn-popup ms-auto mb-0">
                        <a href="{{ route('backend.user.create') }}" class="btn">{{ __('static.users.create') }} </a>
                    </div>
                @endcan
                @can('backend.user.destroy')
                <a href="javascript:void(0);" class="btn btn-sm btn-secondary deleteConfirmationBtn" style="display: none;" data-url="{{ route('backend.delete.users') }}">
                    <span id="count-selected-rows">0</span>{{__('static.delete_selected')}}
                </a>
                @endcan
            </div>
        </div>
        <div class="card-body common-table">
            <div class="user-table">
                <div class="table-responsive">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
{!! $dataTable->scripts() !!}
<script>
    (function($) {
        "use strict";

        $(document).ready(function() {
            $(".credit-wallet").click(function() {
                $("input[name='type']").val("credit");
            });

            $(".debit-wallet").click(function() {
                $("input[name='type']").val("debit");
            });
        });

    })(jQuery);
</script>

@endpush
