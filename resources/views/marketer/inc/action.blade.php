@use('app\Helpers\Helpers')
@use('App\Enums\RoleEnum')
<div class="action-div">
    @isset($data)
        @isset($show)
            <a href="{{ route($show, $data) }}" class="booking-icon show-icon">
                <i data-feather="eye"></i>
            </a>
        @endisset
        @isset($review)
            @can($review_permission ?? $review, $data)
                <!-- Button trigger modal -->
                <a href="javascript:void(0)" class="booking-icon show-icon" data-bs-toggle="modal"
                    data-bs-target="#reviewModal{{ $data->id }}">
                    <i data-feather="eye"></i>
                </a>

                <!-- Modal -->
                <div class="modal fade review-modal" id="reviewModal{{ $data->id }}">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <form action="{{ route('backend.review.update', $data->id) }}" id="updateReview{{ $data->id }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="reviewModalLabel{{ $data->id }}">Edit Review</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label class="col-2 text-start mb-0"
                                            for="rating">{{ __('static.serviceman.rating') }}</label>
                                        <div class="col-10">
                                            <input type="number" name="rating" id="rating" class="form-control" step="0.01"
                                                min="0" max="5" value="{{ $data->rating ?? old('rating') }}"
                                                placeholder="Enter rating (e.g., 4.5)" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-2 text-start mb-0"
                                            for="description">{{ __('static.description') }}</label></label>
                                        <div class="col-10">
                                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Type Here..." required>{{ $data->description ?? old('description') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Update Review</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        @endisset
        @isset($withdraw_request)
            <a href="javascript:void(0)" data-bs-toggle="modal" class="show-icon"
                data-bs-target="#withdrawModal{{ $data->id }}">
                <i data-feather="eye"></i>
            </a>
            <div class="modal fade withdrow-modal" id="withdrawModal{{ $data->id }}" tabindex="-1"
                aria-labelledby="exampleModalLabel{{ $data->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('static.withdraw.title') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <i data-feather="x"></i>
                            </button>
                        </div>
                        <form method="post" id="withdrawalRequestForm" action="{{ route($withdraw_request, $data->id) }}">
                            <div class="modal-body text-start">
                                @csrf
                                @method('put')
                                <div class="table-responsive modal-table">
                                    <table class="table mt-0">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    {{ __('static.wallet.amount') }}
                                                </td>
                                                <td>

                                                    <input class="form-control" type="number" name="amount"
                                                        placeholder="{{ __('static.withdraw.enter_amount') }}"
                                                        value="{{ $data->amount ?? old('amount') }}" readonly>
                                                    @error('amount')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    {{ __('static.withdraw.payment_type') }}
                                                </td>
                                                <td>
                                                    <input class="form-control" type="text" name="payment_type"
                                                        placeholder="{{ __('static.withdraw.payment_type') }}"
                                                        value="{{ $data->payment_type ?? old('payment_type') }}" readonly>
                                                    @error('payment_type')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    {{ __('static.withdraw.message') }}
                                                </td>
                                                <td>
                                                    <p class="modal-message" id="message" readonly>
                                                        {{ $data->message ?? old('message') }}</p>
                                                    {{-- <input class="form-control" name="message" id="message" placeholder="{{ __('static.withdraw.enter_message') }}" readonly value="{{$data->message??old('message')}}"> --}}
                                                    @error('message')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    {{ __('static.status') }}
                                                </td>
                                                <td>
                                                    <input class="form-control" type="text" name="status"
                                                        placeholder="{{ __('static.status') }}"
                                                        value="{{ $data->status ?? old('status') }}" readonly>
                                                    @error('status')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </td>
                                            </tr>
                                            @isset($data->admin_message)
                                                <tr>
                                                    <td>
                                                        {{ __('static.withdraw.admin_message') }}
                                                    </td>
                                                    <td>
                                                        <p class="modal-message" id="admin_message" readonly>
                                                            {{ $data->admin_message ?? old('message') }}
                                                        </p>
                                                        {{-- <textarea class="form-control" name="admin_message" id="admin_message" rows="1" placeholder="{{ __('static.withdraw.enter_admin_message') }}" readonly>{{$data->admin_message??old('message')}}</textarea> --}}
                                                        @error('message')
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </td>
                                                </tr>
                                            @endisset
                                            <input type="hidden" name="provider_id" value="{{ $data->provider_id }}">
                                        </tbody>
                                    </table>
                                </div>
                                @if (!$data->is_used || (!@$data->is_used_by_admin && Helpers::getCurrentRoleName() != RoleEnum::PROVIDER))
                                    @can(@$permission)
                                        <div class="form-group row mt-3">
                                            <label class="col-12"
                                                for="admin_message">{{ __('static.withdraw.message') }}</label></label>
                                            <div class="col-12">
                                                <textarea class="form-control" name="admin_message" id="" rows="3" placeholder="Type Here..."
                                                    @if ($data->is_used) disabled @endif>{{ $data->admin_message ?? old('admin_message') }}</textarea>
                                            </div>
                                        </div>
                                    @endcan
                                @endif
                            </div>
                            @if (!$data->is_used || (!@$data->is_used_by_admin && Helpers::getCurrentRoleName() != RoleEnum::PROVIDER))
                                @if ($data->status === 'pending')
                                    @can(@$permission)
                                        <div class="modal-footer pt-2">
                                            <button class="btn btn-secondary submit-form rejected spinner-btn delete-btn"
                                                type="submit" value="rejected"
                                                name="submit">{{ __('static.rejected') }}</button>
                                            <button class="btn btn-primary submit-form accept spinner-btn delete-btn"
                                                type="submit" value="approved"
                                                name="submit">{{ __('static.accept') }}</button>
                                        </div>
                                    @endcan
                                @endif
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        @endisset
        @isset($wallet)
            @canAny(['backend.wallet.credit', 'backend.wallet.debit'])
                <a href="javascript:void(0)" class="wallet-icon" data-bs-toggle="modal"
                    data-bs-target="#walletmodal{{ $data->id }}">
                    <i data-feather="credit-card"></i>
                </a>
                <div class="modal fade wallet-modal" id="walletmodal{{ $data->id }}" tabindex="-1"
                    aria-labelledby="walletmodalLabel{{ $data->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route($wallet) }}" method="POST" id="creditOrdDebitForm">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-body text-start">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <i data-feather="credit-card" class="wallet-icon"></i>
                                        <div class="form-group row amount wallet">
                                            <h5 for="wallet">
                                                {{ __('static.wallet.balance') }}
                                            </h5>
                                            <h3 id="wallet">
                                                <span>{{ \App\Helpers\Helpers::getSettings()['general']['default_currency']->symbol }}</span>
                                                @if ($data->wallet)
                                                    {{ $data->wallet->balance }}
                                                @else
                                                    0
                                                @endif
                                            </h3>
                                        </div>
                                    </div>
                                    <input type="hidden" class="consumerId" name="consumer_id" value="{{ $data->id }}">
                                    <input type="hidden" class="type" name="type">
                                    <div class="form-group row amount">
                                        <label class="col-md-2"
                                            for="{{ __('static.wallet.amount') }}">{{ __('static.wallet.amount') }}<span>
                                                *</span> </label>
                                        <div class="col-md-10 error-div">
                                            <div class="input-group mb-3 flex-nowrap">
                                                <span
                                                    class="input-group-text">{{ \App\Helpers\Helpers::getSettings()['general']['default_currency']->symbol }}</span>
                                                <div class="w-100">
                                                    <input class="form-control balance" type="number"
                                                        placeholder="{{ __('static.wallet.add_amount') }}" id="balance"
                                                        name="balance" value="{{ old('balance') }}" min="1" required>
                                                    @error('balance')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <span id="balance-error" class="text-danger mt-1"></span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    @can('backend.wallet.credit')
                                        <button type="submit" class="credit btn btn-success credit-wallet">
                                            {{ __('static.wallet.credit') }}
                                            <i data-feather="arrow-down-circle"></i>
                                        </button>
                                    @endcan
                                    @can('backend.wallet.debit')
                                        <button type="submit" class="debit btn btn-danger debit-wallet">
                                            {{ __('static.wallet.debit') }}
                                            <i data-feather="arrow-up-circle"></i>
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endcanAny
        @endisset
        @isset($providerWallet)
            @canAny(['backend.provider_wallet.credit', 'backend.provider_wallet.debit'])
                <a href="javascript:void(0)" class="wallet-icon" data-bs-toggle="modal"
                    data-bs-target="#walletmodal{{ $data->id }}">
                    <i data-feather="credit-card"></i>
                </a>
                <div class="modal fade wallet-modal" id="walletmodal{{ $data->id }}" tabindex="-1"
                    aria-labelledby="walletmodalLabel{{ $data->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route($providerWallet) }}" method="POST" id="providerCreditOrdDebitForm">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-body text-start">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <i data-feather="credit-card" class="wallet-icon"></i>
                                        <div class="form-group row amount wallet">
                                            <h5 for="wallet">
                                                Wallet Balance
                                            </h5>
                                            <h3 id="wallet">
                                                <span>{{ \App\Helpers\Helpers::getSettings()['general']['default_currency']->symbol }}</span>
                                                @if ($data->providerWallet)
                                                    {{ $data->providerWallet->balance }}
                                                @else
                                                    0
                                                @endif
                                            </h3>
                                        </div>
                                    </div>
                                    <input type="hidden" class="consumerId" name="consumer_id" value="{{ $data->id }}">
                                    <input type="hidden" class="type" name="type">
                                    <div class="form-group row amount">
                                        <label class="col-md-2"
                                            for="{{ __('static.wallet.amount') }}">{{ __('static.wallet.amount') }}<span>
                                                *</span> </label>
                                        <div class="col-md-10 error-div">
                                            <div class="input-group mb-3 flex-nowrap">
                                                <span
                                                    class="input-group-text">{{ \App\Helpers\Helpers::getSettings()['general']['default_currency']->symbol }}</span>
                                                <div class="w-100">
                                                    <input class="form-control balance" type="number"
                                                        placeholder="{{ __('static.wallet.add_amount') }}" id="balance"
                                                        name="balance" value="{{ old('balance') }}" min="1" required>
                                                    @error('balance')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <span id="balance-error" class="text-danger mt-1"></span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    @can('backend.provider_wallet.credit')
                                        <button type="submit" class="credit btn btn-success credit-wallet">
                                            {{ __('static.wallet.credit') }}
                                            <i data-feather="arrow-down-circle"></i>
                                        </button>
                                    @endcan
                                    @can('backend.provider_wallet.debit')
                                        <button type="submit" class="debit btn btn-danger debit-wallet">
                                            {{ __('static.wallet.debit') }}
                                            <i data-feather="arrow-up-circle"></i>
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endcanAny
        @endisset
        @isset($edit)
            @can($edit_permission ?? $edit, $data)
                @if (isset($data->system_reserve) ? !$data->system_reserve : true)
                    <a href="{{ route($edit, $data) }}" class="edit-icon">
                        <i data-feather="edit"></i>
                    @else
                        <a href="javascript:void(0)" class="lock-icon">
                            <i data-feather="lock"></i>
                        </a>
                @endif
            @endcan
        @endisset
        @isset($translate)
            @can($edit_permission ?? $edit, $data)
                <a href="{{ route($translate, ['locale' => $data?->locale]) }}" class="lock-icon">
                    <i data-feather="globe"></i>
                </a>
            @endcan
        @endisset
        @isset($select)
            @can($select)
                <input type="checkbox" name="row" class="rowClass" value="{{ $data->id }}"
                    id="rowId' . {{ $data->id }} . '">
            @endcan
        @endisset
        @isset($delete)
            @can($delete_permission ?? $delete, $data)
                @if (isset($data->system_reserve) ? !$data->system_reserve : true)
                    <a href="#confirmationModal{{ $data->id }}" data-bs-toggle="modal" class="delete-svg">
                        <i data-feather="trash-2" class="remove-icon delete-confirmation"></i>
                    </a>
                    <!-- Delete Confirmation -->
                    <div class="modal fade" id="confirmationModal{{ $data->id }}" tabindex="-1"
                        aria-labelledby="confirmationModalLabel{{ $data->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body text-start">
                                    <div class="main-img">
                                        <i data-feather="trash-2"></i>
                                    </div>
                                    <div class="text-center">
                                        <div class="modal-title"> {{ __('static.delete_message') }}</div>
                                        <p>{{ __('static.delete_note') }}</p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route($delete, $data->id) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button class="btn cancel" data-bs-dismiss="modal"
                                            type="button">{{ __('static.cancel') }}</button>
                                        <button class="btn btn-primary delete spinner-btn"
                                            type="submit">{{ __('static.delete') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                @endif
            @endcan
            <!-- Multiple Select Delete Confirmation -->
            <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModal"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-start">
                            <div class="main-img">
                                <i data-feather="trash-2"></i>
                            </div>
                            <div class="text-center">
                                <div class="modal-title"> {{ __('static.delete_message') }}</div>
                                <p>{{ __('static.delete_note') }}</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn cancel multi-delete-cancel" id="cancelModalBtn"
                                data-dismiss="modal">{{ __('static.cancel') }}</button>
                            <button type="button" class="btn btn-primary delete spinner-btn"
                                id="confirm-DeleteRows">{{ __('static.delete') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        @endisset
    @endisset
    @isset($collaps)
        @if (isset($collaps['data']))
            <div class="d-inline-flex">
                <a href="{{ $collaps['primary_on_click_url'] }}" class="form-controll"
                    class="badge badge-success"><span>{{ \App\Helpers\Helpers::getSettings()['general']['default_currency']->symbol }}</span>
                    @if ($collaps['data']->wallet)
                        {{ $collaps['data']->wallet->balance }}
                    @else
                        0
                    @endif
                </a>
            </div>
        @endif
        @if (isset($collaps['booking_data']))
            <div class="d-inline-flex">
                <a href="{{ $collaps['primary_on_click_url'] }}" class="form-controll">
                    @if ($collaps['booking_data']->booking_number)
                    
                        #{{ $collaps['booking_data']->booking_number }}
                    @else
                        #N/A
                    @endif
                </a>
            </div>
        @endif
    @endisset
    @isset($toggle)
        <label class="switch">
            <input data-bs-toggle="modal" data-route="{{ route($route, $toggle->id) }}" data-id="{{ $toggle->id }}"
                class="form-check-input toggle-status" type="checkbox" name="{{ $name }}"
                value="{{ $value }}" {{ $value ? 'checked' : '' }}
                @if ($toggle->system_reserve) disabled @endif>
            <span class="switch-state"></span>
        </label>
    @endisset



    @if (isset($info))
        @php
            $media = $info->getFirstMedia('image');
            $imageUrl = $media ? $media->getUrl() : null;
        @endphp

        @if (!empty($info->name) && !empty($info->email))
            <div class="user-info">
                <a href="{{ isset($route) ? route($route, $info?->id) : 'javascript:void(0)' }}">
                    @if ($imageUrl)
                        <img src="{{ $imageUrl }}" alt="Image" class="img-thumbnail img-fix m-0">
                    @else
                        <div class="initial-letter">{{ strtoupper(substr($info?->name, 0, 1)) }}</div>
                    @endif
                </a>
                <div class="user-details">
                    <a href="{{ isset($route) ? route($route, $info?->id) : 'javascript:void(0)' }}">
                        <h4 class="user-name">
                            {{ $info?->name }}
                            @isset($ratings)
                                <div class="rate">
                                    <img src="{{ asset('admin/images/svg/star.svg') }}" alt="star"
                                        class="img-fluid star">
                                    <small>{{ $ratings }}</small>
                                </div>
                            @endisset
                        </h4>
                    </a>
                    <h6 class="user-email">
                        <a href="mailto:{{ $info?->email }}">{{ $info?->email }}</a>
                        <i data-feather="copy" class="copy-icon-{{ $info->id }}"
                            data-email="{{ $info?->email }}"></i>
                    </h6>
                </div>
            </div>
        @else
            <P>N/A</p>
        @endif
    @endif

    @if (isset($categories))
        <div class="select-service-box">
            @forelse ($categories as $category)
                <div class="selected-booking">
                    <span class="text-capitalize">{{ $category }}</span>
                </div>
            @empty
                N/A
            @endforelse
        </div>
    @endif
</div>

<script>
    (function($) {
        "use strict";
        $(document).ready(function() {
            @isset($review)
                $("#updateReview").validate();
            @endisset
            @isset($wallet)
                $("#creditOrdDebitForm").validate();
                @can('backend.wallet.credit')
                    $(".credit").click(function() {
                        $('input[name="type"]').val('credit');
                    });
                    $(".debit").click(function() {
                        $('input[name="type"]').val('debit');
                    });
                @endcan
            @endisset
            @isset($providerWallet)
                $("#providerCreditOrdDebitForm").validate();
                @can('backend.provider_wallet.credit')
                    $(".credit").click(function() {
                        $('input[name="type"]').val('credit');
                    });
                    $(".debit").click(function() {
                        $('input[name="type"]').val('debit');
                    });
                @endcan
            @endisset
            var id = "{{ isset($info) ? $info->id : '' }}";
            var copyIcon = '.copy-icon-' + id;


            $(document).on('click', copyIcon, function() {
                const $icon = $(this);
                const email = $icon.data('email');
                const originalIcon = $icon.attr('data-feather');
                navigator.clipboard.writeText(email).then(() => {

                    $icon.attr('data-feather', 'check');
                    feather.replace();

                    setTimeout(() => {
                        $icon.attr('data-feather', originalIcon);
                        feather
                            .replace();
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy text: ', err);
                });
            });






        });
    })(jQuery);
</script>
