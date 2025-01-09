@use('App\Models\Booking')
@use('App\Models\CommissionHistory')
@use('app\Helpers\Helpers')
@use('App\Enums\BookingEnum')
@use('App\Enums\BookingEnumSlug')
@extends('marketer.layouts.master')
@section('title', __('static.marketer.downline_marketer'))
@section('breadcrumbs')
<li class="breadcrumb-item active">{{ __('static.marketer.downline_marketer') }}</li>
@endsection

@push('css')
<style>

/* Style the tab */
.tab {
  overflow: hidden;
  border-bottom: 1px solid #ccc;
  text-align: center;
}

/* Style the buttons inside the tab */
.tab button {
  background-color: inherit;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 17px;
  font-weight: 600;
}

/* Change background color of buttons on hover */

/* Create an active/current tablink class */
.tab button.active {
    border-bottom: 2px solid #FF9500;
    color: #FF9500;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
}
</style>
@endpush
@section('content')
<div class="row g-sm-4 g-3">
    <div class="card">
        <div class="card-header">
            <h4><b>@lang('static.marketer.downline_marketer')</b></h4>
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
            <div class="col-12 gap-sm-3 gap-2 mt-5">
                <div class="d-flex gap-3 align-items-end">
                    <div style="width: 100px;height: 1px;background: #dfd4d4;"></div>
                    <h5 style="text-wrap: nowrap;line-height: 16px;">@lang('static.marketer.select_service_zone')</h5>
                    <div style="width: 100%;height: 1px;background: #dfd4d4;"></div>
                </div>
            </div>
            <div class="col-12 d-flex gap-2 p-3">
                <div class="col-3 mt-2">
                    <label for="" class="form-label">Country</label>
                    <select name="" id="" class="form-control bg-white"> 
                        <option value="">Bangladesh</option>
                    </select>
                </div>
                <div class="col-3 mt-2">
                    <label for="" class="form-label">Division</label>
                    <select name="" id="" class="form-control bg-white"> 
                        <option value="">Chittagong</option>
                    </select>
                </div>
                <div class="col-3 mt-2">
                    <label for="" class="form-label">District</label>
                    <select name="" id="" class="form-control bg-white"> 
                        <option value="">Feni</option>
                    </select>
                </div>
                <div class="col-3 mt-2">
                    <label for="" class="form-label">Service Zone</label>
                    <select name="" id="" class="form-control bg-white"> 
                        <option value="">Feni Town</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="container table-responsive py-5"> 
                <div class="tab" style="display: flex;gap: 100px !important;justify-content: center;">
                    <button class="tablinks" onclick="openCity(event, 'Tab1')">Layer 1 (26)</button>
                    <button class="tablinks" onclick="openCity(event, 'Tab2')">Layer 2 (569)</button>
                    <button class="tablinks" onclick="openCity(event, 'Tab3')">Layer 3 (3,057)</button>
                    <button class="tablinks" onclick="openCity(event, 'Tab4')">Downline Team</button>
                </div>
                <div id="Tab1" class="tabcontent">
                    <h3>London</h3>
                    <p>Content1</p>
                </div>
                <div id="Tab2" class="tabcontent">
                    <h3>Paris</h3>
                    <p>Content2</p> 
                </div>
                <div id="Tab3" class="tabcontent">
                    <h3>Tokyo</h3>
                    <p>Content3</p>
                </div>
                <div id="Tab4" class="tabcontent">
                    <h3>Tokyo</h3>
                    <p>Content4</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" inactive", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " inactive";
}
</script>
<script src="{{ asset('admin/js/apex-chart.js') }}"></script>

@endpush