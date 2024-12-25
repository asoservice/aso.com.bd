<div class="d-flex align-items-center justify-content-between flex-wrap mb-2">
<h3>{{__('frontend::static.bookings.select_service_delivery')}}</h3>
<a class="add-location text-underline" data-bs-toggle="modal" data-bs-target="#locationModal">
  + {{__('frontend::static.bookings.add_new_address')}}
</a>
</div>

<div class="row g-4">
  @php
  $addresses =
  auth()?->user()?->addresses?->toArray() ??
  session('addresses', []);
  @endphp
  @forelse ($addresses as $serviceAddress)
  <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-12">
    <div class="card delivery-location">
        <div class="location-header">
          <div class="d-flex align-items-center gap-3">
            <div class="location-icon">
              <img src="{{ asset('frontend/images/svg/home-1.svg') }}"
                alt="home">
            </div>
            <div class="name">
              <h4>{{ $serviceAddress['alternative_name'] ?? auth()?->user()?->name }}
              </h4>
              <span>({{ $serviceAddress['code'] ?? auth()?->user()?->code }})
                {{ $serviceAddress['alternative_phone'] ?? auth()?->user()?->phone }}</span>
            </div>
          </div>
          <span
            class="badge primary-light-badge">{{ $serviceAddress['type'] }}</span>
        </div>
        <div class="address">
          <label>
          {{__('frontend::static.bookings.address')}}
          </label>
          <p>{{ $serviceAddress['address'] }}
            ,{{ $serviceAddress['state']['name'] ?? $serviceAddress?->state?->name }}
            -
            {{ $serviceAddress['postal_code'] ?? $serviceAddress?->postal_code }},
            {{ $serviceAddress['country']['name'] ?? $serviceAddress?->country?->name }}
          </p>
        </div>
        <div class="address-bottom-box">
          <div class="action">
            <input class="radio address-select" type="radio" value="{{ $serviceAddress['id'] ?? null }}" name="{{$name ?? 'address_id'}}">
            <button type="button" type="button" class="btn select-btn btn-outline">{{__('frontend::static.bookings.select_this')}}</button>
          </div>
        </div>
    </div>
  </div>
  @empty
  <div class="col-12">
    <div class="no-data-found">
      <p> {{__('frontend::static.bookings.address_not_found')}}</p>
    </div>
  </div>
  @endforelse
</div>

