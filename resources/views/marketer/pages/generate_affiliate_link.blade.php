@use('App\Models\Booking')
@use('App\Models\CommissionHistory')
@use('app\Helpers\Helpers')
@use('App\Enums\BookingEnum')
@use('App\Enums\BookingEnumSlug')
@extends('marketer.layouts.master')
@section('title', __('static.marketer.generate_affiliate_link'))
@section('breadcrumbs')
<li class="breadcrumb-item active">{{ __('static.marketer.generate_affiliate_link') }}</li>
@endsection


@push('css')
<style>
.copy-link {
  --height: 36px;

  display: flex;
  max-width: 250px;
}

.copy-link-input {
  flex-grow: 1;
  padding: 0 8px;
  font-size: 14px;
  border: 1px solid #cccccc;
  border-right: none;
  outline: none;
}

.copy-link-input:hover {
  background: #eeeeee;
}

.copy-link-button {
  flex-shrink: 0;
  width: var(--height);
  height: var(--height);
  display: flex;
  align-items: center;
  justify-content: center;
  background: #dddddd;
  color: #333333;
  outline: none;
  border: 1px solid #cccccc;
  cursor: pointer;
}

.copy-link-button:hover {
  background: #cccccc;
}

</style>
@endpush


@section('content')
<div class="row g-sm-4 g-3">
    <div class="card">
        <div class="card-header">
            <h4><b>@lang('static.marketer.generate_affiliate_link')</b></h4>
            <div class="btn-action">
                <div class="row">
                    <div class="col-6">
                        <h4 style="color: #009417;"><b>৳ 230.32</b></h4>
                        <span>My Affiliate Earnings (Last 30 days)</span>
                    </div>
                    <div class="col-6">
                        <h4 style="color:rgb(243, 13, 9);"><b>৳ 510.32</b></h4>
                        <span>Affiliate Wallet Balance</span>
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex align-items-center gap-sm-3 gap-2 p-0">
                <img class="active-icon" src="{{ asset('frontend/images/svg/send.svg') }}">
                <h6> Default Affiliate Link: </h6>
                <div class="copy-link">
                    <input type="text" class="copy-link-input" value="https://asoaffiliate.com/?ref=1" readonly>
                    <button type="button" class="copy-link-button">
                        <img class="active-icon" src="{{ asset('frontend/images/svg/copy.svg') }}">
                    </button>
                </div>
            </div>
            <div class="col-12 gap-sm-3 gap-2 p-3">
                <h6 style="margin-left: 60px;"><b>Affiliate Link Generator</b></h6>
                <p class="p-3">If you'd like to add your own affiliate links with a different URL, follow this structure. Enter any URL from this website to generate a referral link.</p>
            </div>
        </div>
        <div class="align-items-center p-0" style="width: 63%;margin-left: 35px;">
            <div class="row">
                <div class="col-5 mt-2">
                    <label for="" class="form-label">Campaign:</label>
                    <select name="" id="" class="form-control bg-white"> 
                        <option value="">Cleaning Service campaign</option>
                    </select>
                </div>
                <div class="col-7 mt-2">
                    <label for="" class="form-label">Specific Website page:</label>
                    <input type="text" class="form-control bg-white">
                </div>
            </div>
        </div>
        <div class="d-flex gap-sm-3 gap-2 p-3" style="margin-left: 35px;">
            <button class="btn btn-danger" style="width: 27%;">Generate Link</button>
            <img class="active-icon" src="{{ asset('frontend/images/svg/send.svg') }}" style="margin-left: 13px;">
            <div class="copy-link">
                <input type="text" class="copy-link-input" value="https://aso.com.bd/category/cleaning/?ref=1" readonly>
                <button type="button" class="copy-link-button">
                    <img class="active-icon" src="{{ asset('frontend/images/svg/copy.svg') }}">
                </button>
            </div>
        </div>
        <div class="row mt-5 mb-5">
            <div class="col-xxl-4 col-xl-12 col-12">
                <a href="{{ route('backend.provider.index') }}" class="widget-card card">
                    <div>
                        <h3>5.89%</h3>
                        <h5>Conversion Rate</h5>
                    </div>
                </a>
            </div>
            <div class="col-xxl-3 col-xl-12 col-12">
                <a href="{{ route('backend.serviceman.index') }}" class="widget-card card">
                    <div>
                        <h3>31,210</h3>
                        <h5>Total Click</h5>
                    </div>
                </a>
            </div>
            <div class="col-xxl-3 col-xl-12 col-12">
                <a href="{{ route('backend.serviceman.index') }}" class="widget-card card">
                    <div>
                        <h3>৳ 10,520</h3>
                        <h5>Total Order | 89</h5>
                    </div>
                </a>
            </div>
        </div>
        <div class="card-body pb-0">
            <table class="table table-striped table-responsive">
                <thead>
                    <tr>
                        <th>Affiliate Link</th>
                        <th>Campaign</th>
                        <th>Created</th>
                        <th>Visits</th>
                        <th>Order</th>
                        <th>Conversion</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                    </tr>
                    <tr>
                        <th>1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                    </tr>
                    <tr>
                        <th>1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                    </tr>
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('admin/js/apex-chart.js') }}"></script>
<script>
    document.querySelectorAll(".copy-link").forEach((copyLinkParent) => {
  const inputField = copyLinkParent.querySelector(".copy-link-input");
  const copyButton = copyLinkParent.querySelector(".copy-link-button");
  const text = inputField.value;

  inputField.addEventListener("focus", () => inputField.select());

  copyButton.addEventListener("click", () => {
    inputField.select();
    navigator.clipboard.writeText(text);

    inputField.value = "Copied!";
    setTimeout(() => (inputField.value = text), 2000);
  });
});

</script>
@endpush