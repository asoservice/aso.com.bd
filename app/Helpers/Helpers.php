<?php

namespace App\Helpers;

use App\Enums\ServiceTypeEnum;
use Exception;
use App\SMS\SMS;
use App\Enums\BannerTypeEnum;
use App\Enums\BookingEnum;
use App\Enums\BookingStatusReq;
use App\Enums\FrontEnum;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\RoleEnum;
use App\Enums\SortByEnum;
use App\Models\Address;
use App\Models\BankDetail;
use App\Models\Blog;
use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\Category;
use App\Models\Country;
use App\Models\Currency;
use App\Models\ExtraCharge;
use App\Models\HomePage;
use App\Models\Module;
use App\Models\ProviderWallet;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServicemanWallet;
use App\Models\ServicePackage;
use App\Models\CustomSmsGateway;
use App\Models\Setting;
use App\Models\State;
use App\Models\SystemLang;
use App\Models\Testimonial;
use App\Models\ThemeOption;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Zone;
use Carbon\Carbon;
use Google_Client;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Modules\Coupon\Entities\Coupon;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Nwidart\Modules\Facades\Module as NwidartModule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait RoleTrait {
    public static function hasRole(string $roleName, int $userId = null){
        if(!Auth::check()) return false; // if use is not authorized

        if(is_null($userId)) $userId = Auth::id();
        $user = User::find($userId);

        if($user) return $user->hasRole($roleName);
        return false;
    }

    public static function getRoleDetails(int $userId = null){
        if(!Auth::check()) return ['Unknown'];

        if(is_null($userId)) $userId = Auth::id();
        $user = User::find($userId);

        if($user) return $user->roles()->get();
        return ['Unknown'];
    }

    public static function slug($model, string $string = '1')
    {
        $slug = Str::slug($string);

        if ($model->where('slug', $slug)->exists()) {
            $counter = 1;

            while ($model->where('slug', $slug . '-' . $counter)->exists()) {
                $counter++;
            }

            $slug = $slug . '-' . $counter;
        }

        return $slug;
    }

    public static function storeFile($file, string $path = 'storage/media/uploads/', $oldImage = NULL)
    {
        $createPath = public_path($path);
        if (!File::isDirectory($createPath)) {
            File::makeDirectory($createPath, 0777, true, true);
        }

        $ext = $file ? $file->getClientOriginalExtension() : 'jpg';
        $filename = Carbon::now()->toDateString() . '-' . Str::random() . '.' . $ext;
        
        // $ext = 'webp';
        // $file = Image::make($file);
        // $file->resize($size, null, function ($constraint) {
            //     $constraint->aspectRatio();
            //     $constraint->upsize();
            // })->stream($ext, 100);
        
        $filenameWithPath = $path . $filename;
        $file->move($createPath, $filename);

        if (file_exists($oldImage)) {
            unlink($oldImage);
        }

        return $filenameWithPath;
    }
}

class Helpers
{
    use RoleTrait;

    public static function canCancelBooking($booking)
    {
        $settings = self::getSettings();
        if (isset($settings['general']['cancellation_restriction_hours'])) {
            $bookingDateTime = Carbon::parse($booking->date_time);
            $cutoffTime = $bookingDateTime->subHours($settings['general']['cancellation_restriction_hours']);
            return Carbon::now()->isBefore($cutoffTime);
        }
        return true;
    }

    public static function getEncrypter()
    {
        return App::make('encrypter');
    }

    public static function isEncrypted($key)
    {
        return strpos($key, 'eyJpdiI') === 0;
    }

    public static function getServiceIds() {}


    public static function encryptKey($key)
    {
        if (config('app.demo')) {
            if ($key) {
                return Self::getEncrypter()?->encrypt($key);
            }
        }

        return $key;
    }

    public static function decryptKey($key)
    {
        if (config('app.demo')) {
            if (self::isEncrypted($key)) {
                return self::getEncrypter()?->decrypt($key);
            }

            return $key;
        }

        return $key;
    }

    public static function getSmsGatewaySettings()
    {
        return CustomSmsGateway::first();
    }

      

    //removed
    public static function sendSMS($sendTo, $message)
    {
        try {
            $defaultSMSGateway = self::getDefaultSMSGateway();
            if($defaultSMSGateway){
                if($defaultSMSGateway == 'custom'){
                    $sms = new SMS();
                    $data['to'] = $sendTo;
                    $data['message'] = $message;
                    
                    $sms->sendSMS($data);
                }
                $module = NwidartModule::find($defaultSMSGateway);
                if ($module) {
                    if (!is_null($module) && $module?->isEnabled()) {
                        $moduleName = $module->getName();
                        $sms = 'Modules\\' . $moduleName . '\\SMS\\' . $moduleName;
                        if (class_exists($sms) && method_exists($sms, 'getIntent')) {
                            return $sms::getIntent($sendTo, $message);
                        }
                    }
                }   
            }
        } catch (Exception $e) {
        }
    }

    public static function modules()
    {
        return Module::get();
    }

    public static function isUserLogin()
    {
        return Auth::guard('api')->check();
    }

    public static function isFirstAddress($address)
    {
        if ($address) {
            if ($address->user_id) {
                $addresses = Address::where('user_id', $address->user_id)->count();
            } else {
                $addresses = Address::where('service_id', $address->service_id)->count();
            }

            return $addresses > 1;
        }

        return true;
    }

    public static function getBookingById($id)
    {
        return Booking::findOrFail($id)?->first();
    }

    public static function getCountries()
    {
        return Country::pluck('name', 'id');
    }

    public static function getCountryCodes()
    {
        return Country::get(['name', 'phone_code', 'id', 'iso_3166_2', 'flag'])->unique('phone_code');
    }

    public static function getStatesByCountryId($countryId)
    {
        return State::where('country_id', $countryId)->get(['name', 'id']);
    }

    public static function getCountryCode()
    {
        return Country::get(['phone_code', 'id', 'iso_3166_2', 'flag'])->unique('phone_code');
    }

    public static function getConsumerById($consumer_id)
    {
        return User::whereNull('deleted_at')->where('id', $consumer_id)->first();
    }

    public static function getZoneByPoint($latitude, $longitude)
    {
        $lat = (float) $latitude;
        $lng = (float) $longitude;
        $point = new Point($lat, $lng);
        return Zone::whereContains('place_points', $point)->get(['id', 'name']);
    }

    public static function mediaUpload($modelName, $fileName)
    {
        $media = $modelName->addMediaFromRequest($fileName)->toMediaCollection($fileName);
        $modelName->profile_image_url = $media->getFullUrl();
        $modelName->save();
    }

    public static function getRelatedServiceId($model, $category_id, $service_id)
    {
        return $model->whereRelation(
            'categories',
            function ($categories) use ($category_id) {
                $categories->Where('category_id', $category_id);
            }
        )->whereNot('id', $service_id)->pluck('id')->toArray();
    }

    public static function getWalletIdByUserId($userId)
    {
        return Wallet::where('consumer_id', $userId)->pluck('id')->first();
    }

    public static function getProviderWalletIdByproviderId($providerId)
    {
        return ProviderWallet::where('provider_id', $providerId)->pluck('id')->first();
    }

    public static function getTestimonials($paginate = null)
    {
        return Testimonial::paginate($paginate);
    }

    public static function getServicePackagesByIds($ids, $paginate = null)
    {
        return ServicePackage::whereIn('id', $ids)?->whereNull('deleted_at')?->paginate($paginate);
    }

    public static function getServicePackageById($ids)
    {
        return ServicePackage::where('id', $ids)?->whereNull('deleted_at')->first();
    }

    public static function getBlogsByIds($ids, $paginate = null)
    {
        return Blog::whereIn('id', $ids)?->whereNull('deleted_at')?->paginate($paginate);
    }

    public static function getCurrencyByCode($code)
    {
        return Currency::where('code', $code)?->whereNull('deleted_at')?->first();
    }

    public static function getDefaultCurrencySymbol()
    {
        if (session('currency')) {
            $currency = self::getCurrencyByCode(session('currency'));
            if ($currency) {
                return $currency?->symbol;
            }
        }

        $settings = self::getSettings();
        if (isset($settings['general']['default_currency'])) {
            $currency = $settings['general']['default_currency'];
            return $currency?->symbol;
        }
    }

    public static function getActiveCurrencies()
    {
        return Currency::where('status', true)?->whereNull('deleted_at')?->get();
    }

    public static function getServicemanWalletIdByServicemanId($serviceman_id)
    {
        return ServicemanWallet::where('serviceman_id', $serviceman_id)->pluck('id')->first();
    }

    public static function getProviders()
    {
        return User::role('provider')->whereNull('deleted_at');
    }

    public static function getProvidersByIds($ids)
    {
        return User::role('provider')->whereNull('deleted_at')->whereIn('id',$ids);
    }

    public static function getTopProvidersByRatings($provider_ids = [])
    {
        $providers = self::getProviders()?->get()->filter(function ($provider) {
            return $provider->review_ratings >= 0;
        });
        
        if(count($provider_ids)) {
            $providers = self::getProvidersByIds($provider_ids)?->get()->filter(function ($provider) {
                    return $provider->review_ratings >= 0;
            });
        }

        $providers->sortByDesc('review_ratings');
        return $providers;
    }

    public static function getServiceRequestSettings()
    {
        $settings = self::getSettings();
        if (isset($settings['service_request'])) {
            return $settings['service_request'];
        }
    }

    public static function getDefaultCurrencyCode()
    {
        if (session('currency')) {
            return session('currency');
        }

        $settings = self::getSettings();
        if (isset($settings['general']['default_currency'])) {
            $currency = $settings['general']['default_currency'];

            return $currency->code;
        }
    }

    public static function covertDefaultExchangeRate($amount)
    {
        return self::currencyConvert(self::getDefaultCurrencyCode(), $amount);
    }

    public static function getCurrencyExchangeRate($currencyCode)
    {
        return Currency::where('code', $currencyCode)?->pluck('exchange_rate')?->first();
    }

    public static function currencyConvert($currencySymbol, $amount)
    {
        $exchangeRate = self::getCurrencyExchangeRate($currencySymbol) ?? 1;
        $price = $amount * $exchangeRate;

        return self::roundNumber($price);
    }

    public static function getWalletBalanceByUserId($userId)
    {
        return Wallet::where('consumer_id', $userId)->pluck('balance')->first();
    }

    public static function getBannerCategories($catgoryType)
    {
        switch ($catgoryType) {
            case BannerTypeEnum::BANNERTYPE['category']:
                return Category::where(['status' => true])->get(['title', 'id']);

            case BannerTypeEnum::BANNERTYPE['provider']:
                return User::role('provider')->where('status', true)->get();
            default:
                return Service::where(['status' => true])->get(['title', 'id']);
        }
    }

    public static function getCurrentRoleName()
    {
        $user = auth()->user();
        if (request()->expectsJson()) {
            $user = Auth::guard('api')->user();
        }

        return $user?->role?->name ?? $user?->roles?->first()?->name;
    }

    public static function getCurrentUser()
    {
        return Auth::guard('api')->user();
    }

    public static function getCurrentUserId()
    {
        return Auth::guard('api')->user()?->id;
    }

    public static function isDefaultLang($id)
    {
        $settings = self::getSettings();
        if ($settings) {
            if (isset($settings['general'])) {
                return $settings['general']['default_language_id'] == $id;
            }
        }
    }

    public static function getCoupon($data)
    {
        return Coupon::where([['code', 'LIKE', '%' . $data . '%'], ['status', true]])
            ->orWhere('id', 'LIKE', '%' . $data . '%')
            ->with(['services', 'exclude_services'])
            ->first();
    }

    public static function isCommandLineInstalled()
    {
        if (env('DB_DATABASE') && env('DB_USERNAME')) {
            DB::connection()->getPDO();
            if (DB::connection()->getDatabaseName()) {
                if (Schema::hasTable('seeders')) {

                    $completeSeeders = DB::table('seeders')
                        ->whereIn('name', config('enums.seeders'))
                        ->where('is_completed', true)->count();

                    if ($completeSeeders == count(config('enums.seeders'))) {
                        Storage::disk('local')->put(
                            config('config.migration'),
                            json_encode(
                                ['application_migration' => 'true']
                            )
                        );

                        return true;
                    }
                }
            }
        }

        return false;
    }

    public static function getFCMAccessToken()
    {
        $client = new Google_Client();
        $client->setAuthConfig(public_path('admin/assets/firebase.json'));
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();

        return $token['access_token'];
    }

    public static function getFirebaseJson()
    {
        $firebaseJson = json_decode(file_get_contents(public_path('admin/assets/firebase.json')), true);
        return $firebaseJson;
    }

    public static function pushNotification($notification)
    {
        try {

            $firebaseJson = self::getFirebaseJson();
            if ($firebaseJson) {
                $ch = curl_init();
                $url = "https://fcm.googleapis.com/v1/projects/{$firebaseJson['project_id']}/messages:send";
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer ' . self::getFCMAccessToken()]);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $result = curl_exec($ch);
                curl_close($ch);
            }
        } catch (Exception $e) {
            
        }
    }

    public static function installation()
    {
        if (! self::isCommandLineInstalled()) {
            if (self::migration()) {
                if (Storage::disk('local')->exists(config('config.installation'))) {
                    $install = json_decode(Storage::get(config('config.installation')));
                    if ($install->application_installation === 'Completed') {
                        return true;
                    }

                    return true;
                }
            }

            return false;
        }

        return true;
    }

    public static function migration()
    {
        if (! self::isCommandLineInstalled()) {
            if (Storage::disk('local')?->exists(config('config.migration')) === true) {
                $install = json_decode(Storage::get(config('config.migration')));
                if ($install->application_migration == 'true') {
                    return true;
                }

                return true;
            }

            return false;
        }

        return true;
    }

    public static function getPaymentAccount($user_id)
    {
        return BankDetail::where('user_id', $user_id)->first();
    }

    public static function getCurrentProviderId()
    {
        if (self::isUserLogin()) {
            return Auth::guard('api')->user()?->id;
        }
    }

    public static function addMedia($model, $media, $collectionName)
    {
        return $model->addMedia($media)->toMediaCollection($collectionName);
    }

    public static function getSettings()
    {
        return Setting::pluck('values')->first();
    }

    public static function getAdmin()
    {
        return User::role(RoleEnum::ADMIN)->first();
    }

    public static function getRoleByUserId($user_id)
    {
        return User::findOrFail($user_id)->getRoleNames()->first();
    }

    public static function getProviderById($provider_id)
    {
        return User::where('id', $provider_id)->first();
    }

    public static function getProviderIdByServiceId($service_id)
    {
        return Service::where('id', $service_id)->pluck('user_id')->first();
    }

    public static function getRoleNameByUserId($user_id)
    {
        return User::find($user_id)?->role?->name;
    }

    public static function getRelatedProductId($model, $category_id, $product_id = null)
    {
        return $model->whereRelation(
            'categories',
            function ($categories) use ($category_id) {
                $categories->Where('category_id', $category_id);
            }
        )->whereNot('id', $product_id)->inRandomOrder()->limit(6)->pluck('id')->toArray();
    }

    public static function getConsumerBooking($consumer_id, $service_id)
    {
        return Booking::where('consumer_id', $consumer_id)
            ->where('service_id', $service_id)->whereNotNull('parent_id')
            ->get();
    }

    public static function roundNumber($numb)
    {
        return number_format($numb, 2, '.', '');
    }

    public static function formatDecimal($value)
    {
        return floor($value * 100) / 100;
    }

    public static function getServicePrice($service_id)
    {
        return Service::where('id', $service_id)->first(['price', 'discount']);
    }

    public static function getBookingStatusIdByName($name)
    {
        return BookingStatus::where('name', $name)->pluck('id')->first();
    }

    public static function getSalePrice($service)
    {
        $servicePrices = self::getPrice($service);

        return $servicePrices->price - (($servicePrices->price * $servicePrices->discount) / 100);
    }

    public static function getServicePackageSalePrice($service_package_id)
    {
        $servicePrices = ServicePackage::where('id', $service_package_id)->first(['price', 'discount']);
        return $servicePrices->price - (($servicePrices->price * $servicePrices->discount) / 100);
    }

    public static function getPackageSalePrice($service_package)
    {
        $packagePrice = self::getServicePackageSalePrice($service_package['service_package_id']);
        $serviceTotal = 0;
        if (!empty($service_package['services'])) {
            foreach ($service_package['services'] as $service) {

                if (!empty($service['additional_services'])) {
                    $serviceTotal = self::getSalePrice($service);
                    foreach ($service['additional_services'] as $additional_service_id) {

                        $serviceTotal += self::getAdditionalServiceSalePrice($additional_service_id);
                    }
                }
                $packagePrice += $serviceTotal;
            }
        }
        return $packagePrice;
    }

    public static function getAdditionalServicePrice($additionalService)
    {
        return Service::where('id', $additionalService)->pluck('price')->first();
    }

    public static function getSubTotal($price, $quantity = 1)
    {
        return $price * $quantity;
    }

    public static function getTotalRequireServicemenByServiceId($service_id)
    {
        return Service::where('id', $service_id)->pluck('required_servicemen')->first();
    }

    public static function getPackageSubTotal($price, $quantity = 1)
    {
        return $price * $quantity;
    }

    public static function getTotalAmount($services, $service_packages)
    {
        $subtotal = [];
        if ($service_packages) {
            foreach ($service_packages as $service_package) {
                $subtotal[] = self::getPackageSalePrice($service_package);
            }
        }

        foreach ($services as $service) {
            $serviceTotal = self::getSalePrice($service);

            if (!empty($service['additional_services'])) {
                foreach ($service['additional_services'] as $additional_service_id) {
                    $serviceTotal += self::getAdditionalServiceSalePrice($additional_service_id);
                }
            }
            $subtotal[] = $serviceTotal;
        }
        return array_sum($subtotal);
    }

    public static function getAdditionalServiceSalePrice($additional_service_id)
    {
        $additionalServicePrices = self::getServicePrice($additional_service_id);
        return $additionalServicePrices?->price;
    }

    public static function getPrice($service)
    {
        return self::getServicePrice($service['service_id']);
    }

    public static function walletIsEnable()
    {
        $settings = self::getSettings();

        return $settings['activation']['wallet_enable'];
    }

    public static function additionalServicesIsEnable()
    {
        $settings = self::getSettings();
        return $settings['activation']['additional_services'];
    }

    public static function couponIsEnable()
    {
        $settings = self::getSettings();

        return $settings['activation']['coupon_enable'];
    }

    public static function getCategoryCommissionRate($categories)
    {
        return Category::whereIn('id', $categories)->pluck('commission_rate');
    }

    public static function getBookingIdBySlug($slug)
    {
        return BookingStatus::where('slug', $slug)->first();
    }

    public static function getBookingStatusIdByReq($req_status)
    {
        $status = $req_status;
        switch ($req_status) {
            case BookingStatusReq::PENDING:
                $status = BookingEnum::PENDING;
                break; 
            case BookingStatusReq::ASSIGNED:
                $status = BookingEnum::ASSIGNED;
                break;
            case BookingStatusReq::ON_THE_WAY:
                $status = BookingEnum::ON_THE_WAY;
                break;
            case BookingStatusReq::DECLINE:
                $status = BookingEnum::DECLINE;
                break;
            case BookingStatusReq::ON_HOLD:
                $status = BookingEnum::ON_HOLD;
                break;

            case BookingStatusReq::START_AGAIN:
                $status = BookingEnum::START_AGAIN;
                break;

            case BookingStatusReq::COMPLETED:
                $status = BookingEnum::COMPLETED;
                break;
        }

        return self::getbookingStatusId($status);
    }

    public static function getbookingStatusIdBySlug($booking_status_slug)
    {
        return BookingStatus::where('slug', $booking_status_slug)?->value('id');
    }

    public static function getbookingStatusId($booking_status)
    {
        return BookingStatus::where('name', $booking_status)?->value('id');
    }

    public static function getbookingStatusName($booking_status_id)
    {
        return BookingStatus::where('name', $booking_status_id)?->value('name');
    }

    public static function getTopSellingServicec($services)
    {
        $orders_count = $services->withCount(['bookings'])->get()->sum('bookings_count');
        $services = $services->orderByDesc('bookings_count');
        if (! $orders_count) {
            $services = (new Service)->newQuery();
            $services->whereRaw('1 = 0');

            return $services;
        }

        return $services;
    }

    public static function getTopVendors($store)
    {
        $store = $store->orderByDesc('orders_count');
        $orders_count = $store->withCount(['orders'])->get()->sum('orders_count');
        if (! $orders_count) {
            $store = (new User)->newQuery();
            $store->whereRaw('1 = 0');

            return $store;
        }

        return $store;
    }

    public static function getProductStock($product_id)
    {
        return Service::where([['id', $product_id], ['status', true]])->first();
    }

    public static function getCountUsedPerConsumer($consumer, $coupon)
    {
        return Booking::where([['consumer_id', $consumer], ['coupon_id', $coupon]])->count();
    }

    public static function isBookingCompleted($bookings)
    {
        foreach ($bookings as $booking) {
            if ($booking->payment_status == PaymentStatus::COMPLETED && $booking->booking_status->name == BookingEnum::COMPLETED) {
                return true;
            }
        }

        return false;
    }

    public static function isAlreadyReviewed($consumer_id, $service_id)
    {
        $review = Review::where([
            ['consumer_id', $consumer_id],
            ['service_id', $service_id],
        ])->exists();
        if (! $review) {
            return true;
        }

        return false;
    }

    public static function isAlreadyReviewedServiceman($consumer_id, $serviceman_id)
    {
        $review = Review::where([
            ['consumer_id', $consumer_id],
            ['serviceman_id', $serviceman_id],
        ])->exists();
        if (! $review) {
            return true;
        }

        return false;
    }

    public static function getFilterBy($model, $filter_by)
    {
        switch ($filter_by) {
            case SortByEnum::TODAY:
                $model = $model->where('created_at', Carbon::now());
                break;

            case SortByEnum::LAST_WEEK:
                $startWeek = Carbon::now()->subWeek()->startOfWeek();
                $endWeek = Carbon::now()->subWeek()->endOfWeek();
                $model = $model->whereBetween('created_at', [$startWeek, $endWeek]);
                break;

            case SortByEnum::LAST_MONTH:
                $model = $model->whereMonth('created_at', Carbon::now()->subMonth()->month);
                break;

            case SortByEnum::THIS_YEAR:
                $model = $model->whereYear('created_at', Carbon::now()->year);
                break;
        }

        return $model;
    }

    public static function getProviderRatingList($provider_id)
    {
        $review = Review::where('provider_id', $provider_id)->get();
        return [
            $review->where('rating', 5)->count(),
            $review->where('rating', 4)->count(),
            $review->where('rating', 3)->count(),
            $review->where('rating', 2)->count(),
            $review->where('rating', 1)->count(),
        ];
    }

    public static function getProviderReviewRatings($provider){
        return $provider->reviews->avg('rating') ? round($provider->reviews->avg('rating'), 2) : 0;
    }

    public static function getServicemanReviewRatings($serviceman){
       
        return $serviceman->servicemanreviews->avg('rating') ? round($serviceman->servicemanreviews->avg('rating'), 2) : 0;
    }

    public static function getServiceManRatingList($serviceman_id)
    {
        $review = Review::where('serviceman_id', $serviceman_id)->get();

        return [
            $review->where('rating', 5)->count(),
            $review->where('rating', 4)->count(),
            $review->where('rating', 3)->count(),
            $review->where('rating', 2)->count(),
            $review->where('rating', 1)->count(),
        ];
    }

    public static function getServiceRatingList($service_id)
    {
        $review = Review::where('service_id', $service_id)->get();

        return [
            $review->where('rating', 5)->count(),
            $review->where('rating', 4)->count(),
            $review->where('rating', 3)->count(),
            $review->where('rating', 2)->count(),
            $review->where('rating', 1)->count(),
        ];
    }

    public static function getReviewRatings($service_id)
    {
        $review = Review::where('service_id', $service_id)->get();

        return [
            $review->where('rating', 1)->count(),
            $review->where('rating', 2)->count(),
            $review->where('rating', 3)->count(),
            $review->where('rating', 4)->count(),
            $review->where('rating', 5)->count(),
        ];
    }

    public static function getRatingPercentages(array $counts, int $total)
    {
        if (count($counts ?? [])) {
            return array_map(fn($count) => $total > 0 ? ($count / $total) * 100 : 0, $counts);
        }

        return [];
    }

    public static function getProvidersCount()
    {
        return User::role(RoleEnum::PROVIDER)->where('system_reserve', false)->count();
    }

    public static function getServicemenCount()
    {
        return User::role(RoleEnum::SERVICEMAN)->where('system_reserve', false)->where('provider_id', auth()->user()->id)->count();
    }

    public static function isZoneExists()
    {
        return Zone::whereNull('deleted_at')?->exists();
    }

    public static function getServicesCount()
    {
        $roleName = self::getCurrentRoleName();
        if ($roleName === RoleEnum::PROVIDER) {
            $services = Service::where('user_id', auth()->user()->id);
        } else {
            $services = Service::all();
        }

        return $services->count();
    }

    public static function getBookingsCount()
    {
        $roleName = self::getCurrentRoleName();
        if ($roleName === RoleEnum::PROVIDER) {
            $bookings = Booking::whereNull('parent_id')->whereHas('sub_bookings', function ($query) {
                $query->where('provider_id', auth()->user()->id);
            })->count();
        } else if ($roleName === RoleEnum::SERVICEMAN) {
            $bookings = Booking::whereHas('servicemen', function ($query) {
                $query->where('users.id', auth()->user()->id);
            })->whereNotNull('parent_id')
                ->count();
        } else {
            $bookings = Booking::whereNotNull('parent_id')?->count();
        }

        return $bookings;
    }

    public static function getCustomersCount()
    {
        return User::role(RoleEnum::CONSUMER)->where('system_reserve', false)->count();
    }

    // Payment Gateway
    public static function isModuleEnable($moduleName)
    {
        return NwidartModule::isEnabled($moduleName);
    }

    public static function getAllModules()
    {
        return NwidartModule::all();
    }

    public static function getLanguages()
    {
        return SystemLang::where('status', true)?->get();
    }

    public static function getPaymentMethodList()
    {
        $settings = self::getSettings();
        $paymentMethods = [];
        $modules = self::getAllModules();
        $paymentMethods[] = [
            'name' => __('static.cash'),
            'slug' => PaymentMethod::COD,
            'image' => null,
            'status' => $settings['activation']['cash'] ? true : false,
        ];
        foreach ($modules as $module) {
            $paymentFile = module_path($module->getName(), 'Config/payment.php');
            if (file_exists($paymentFile)) {
                $payment = include $paymentFile;
                $paymentMethods[] = [
                    'name' => $payment['name'],
                    'slug' => $payment['slug'],
                    'title' => $payment['title'],
                    'image' => url($payment['image']),
                    'status' => $module?->isEnabled(),
                ];
            }
        }

        return $paymentMethods;
    }


    public static function getPaymentModuleList()
    {
        $settings = self::getSettings();
        $paymentMethods = [];
        $modules = self::getAllModules();
        foreach ($modules as $module) {
            $paymentFile = module_path($module->getName(), 'Config/payment.php');
            if (file_exists($paymentFile)) {
                $payment = include $paymentFile;
                $paymentMethods[] = [
                    'name' => $payment['name'],
                    'slug' => $payment['slug'],
                    'title' => $payment['title'],
                    'image' => url($payment['image']),
                    'status' => $module?->isEnabled(),
                ];
            }
        }

        return $paymentMethods;
    }

    public static function getActiveOnlinePaymentMethods()
    {
        $paymentMethods =  self::getPaymentModuleList();
        $filteredMethods = array_filter($paymentMethods, function ($method) {
            return $method['status'] === true;
        });

        return $filteredMethods;
    }

    public static function getActivePaymentMethods()
    {
        $paymentMethods =  self::getPaymentMethodList();
        $filteredMethods = array_filter($paymentMethods, function ($method) {
            return $method['status'] === true;
        });

        return $filteredMethods;
    }

    public static function getPaymentMethodConfigs()
    {
        $paymentMethods = [];
        $modules = self::getAllModules();
        foreach ($modules as $module) {
            $paymentFile = module_path($module->getName(), 'Config/payment.php');
            if (file_exists($paymentFile)) {
                $payment = include $paymentFile;
                $paymentMethods[] = [
                    'name' => $payment['name'],
                    'slug' => $payment['slug'],
                    'title' => $payment['title'],
                    'image' => url($payment['image']),
                    'status' => $module?->isEnabled(),
                    'configs' => $payment['configs'],
                    'fields' => $payment['fields'],
                ];
            }
        }

        return $paymentMethods;
    }

    // SMS Gateways
    public static function getSMSGatewayList()
    {
        $smsGateways = [];
        $modules = self::getAllModules();
        foreach ($modules as $module) {
            $smsFile = module_path($module->getName(), 'Config/sms.php');
            if (file_exists($smsFile)) {
                $sms = include $smsFile;
                $smsGateways[] = [
                    'name' => $sms['name'],
                    'slug' => $sms['slug'],
                    'image' => url($sms['image']),
                    'status' => $module?->isEnabled(),
                ];
            }
        }

        return $smsGateways;
    }


    public static function getSMSGatewayConfigs()
    {
        $smsGateways = [];
        $modules = self::getAllModules();
        foreach ($modules as $module) {
            $smsFile = module_path($module->getName(), 'Config/sms.php');
            if (file_exists($smsFile)) {
                $sms = include $smsFile;
                $smsGateways[] = [
                    'name' => $sms['name'],
                    'slug' => $sms['slug'],
                    'image' => url($sms['image']),
                    'status' => $module?->isEnabled(),
                    'configs' => $sms['configs'],
                    'fields' => $sms['fields'],
                ];
            }
        }

        return $smsGateways;
    }

    public static function getDefaultSMSGateway()
    {
        $settings = self::getSettings();
        return $settings['general']['default_sms_gateway'] ?? null;
    }

    //
    // =================================== Frontend ======================================

    public static function getCurrentHomePage()
    {
        return HomePage::where('status', true)?->pluck('content')?->first();
    }

    public static function getServiceById($id)
    {
        return Service::where('id', $id)?->whereNull('deleted_at')?->first();
    }

    public static function getServiceByProviderId($provider_id)
    {
        return Service::where('user_id', $provider_id)?->where('status', true)?->whereNull('deleted_at')?->whereNull('parent_id')?->where('type', ServiceTypeEnum::FIXED)?->latest()?->paginate();
    }

    public static function getServicesByIds($ids)
    {
        return Service::whereIn('id', $ids)?->whereNull('deleted_at')?->where('type', ServiceTypeEnum::FIXED)?->get();
    }

    public static function getThemeOptions()
    {
        return ThemeOption::first()?->options;
    }

    public static function dateTimeFormat($timestamp, $format)
    {
        return Carbon::parse($timestamp)->format($format);
    }

    public static function getFooterUsefulLinks()
    {
        return [
            [
                'slug' => '/',
                'name' => 'Home',
            ],
            [
                'slug' => 'category',
                'name' => 'Categories',
            ],
            [
                'slug' => 'service',
                'name' => 'Services',
            ],
            [
                'slug' => 'booking',
                'name' => 'Bookings',
            ],
            [
                'slug' => 'blog',
                'name' => 'Blogs',
            ],
            [
                'slug' => 'provider',
                'name' => 'Providers',
            ],
        ];
    }

    public static function getFooterPagesLinks()
    {
        return [
            [
                'slug' => 'privacy-policy',
                'name' => 'Privacy Policy',
            ],
            [
                'slug' => 'terms-conditions',
                'name' => 'Terms & Conditions',
            ],
            [
                'slug' => 'contact-us',
                'name' => 'Contact Us',
            ],
            [
                'slug' => 'about-us',
                'name' => 'About Us',
            ],
        ];
    }

    public static function getFooterOthersLinks()
    {
        return [
            [
                'slug' => 'account/profile',
                'name' => 'My Account',
            ],
            [
                'slug' => 'wishlist',
                'name' => 'Wishlist',
            ],
            [
                'slug' => 'booking',
                'name' => 'Bookings',
            ],
            [
                'slug' => 'providers',
                'name' => 'Providers',
            ],
            [
                'slug' => 'service',
                'name' => 'Services',
            ],
        ];
    }


    public static function getAssetUrl($url)
    {
        return str_replace(config('app.url'), "", $url);
    }

    public static function getServicemenByProviderId($provider_id)
    {
        return User::where('provider_id', $provider_id)?->whereNull('deleted_at')?->get();
    }
    
    public static function getUsersByIds($ids)
    {
        return User::whereIn('id', $ids)?->whereNull('deleted_at')?->get();
    }

    public static function getPerServicemen($service)
    {
        $reqServicemen = $service?->required_servicemen;
        return $service?->service_rate / (($reqServicemen > 0) ? $reqServicemen : 1);
    }

    public static function getServiceCategories()
    {
        return Category::where('status', true)?->where('category_type', 'service')?->whereNull('deleted_at')?->get();
    }

    public static function getCoupons()
    {
        return Coupon::whereNull('deleted_at')?->get();
    }

    public static function getActiveBookingStatusList()
    {
        return BookingStatus::where('status', true)?->whereNull('deleted_at')?->get();
    }

    public static function isExtraChargePaymentPending($booking_id)
    {
        return ExtraCharge::where('booking_id', $booking_id)
            ?->whereNot('payment_status', PaymentStatus::COMPLETED)
            ?->exists() ?? false;
    }

    public static function getTotalExtraCharges($booking_id)
    {
        return ExtraCharge::where('booking_id', $booking_id)?->sum('total');
    }

    public static function getExtraChargePaymentAmount($booking_id)
    {
        return ExtraCharge::where('booking_id', $booking_id)
            ?->whereNot('payment_status', PaymentStatus::COMPLETED)
            ?->sum('total');
    }


    public static function getServicesByZoneIds($zoneIds)
    {
        return Service::whereHas('categories', function (Builder $categories) use ($zoneIds) {
            $categories->whereHas('zones', function (Builder $zones) use ($zoneIds) {
                $zones->WhereIn('zones.id', $zoneIds);
            });
        });
    }

    public static function getCategoriesByZoneIds($zoneIds)
    {
        return Category::whereRelation('zones', function ($zones) use ($zoneIds) {
            $zones->WhereIn('zone_id', $zoneIds);
        });
    }

    public static function getCategoriesByIds($ids)
    {
        return Category::whereIn('id', $ids)?->whereNull('deleted_at')?->get();
    }

    public static function getThemeOptionsPaginate()
    {
        $themeOptions = self::getThemeOptions();
        return $themeOptions['pagination'];
    }

    public static function getServices($ids = [])
    {
        $services = collect();
        if (count(session('zoneIds', []))) {
            $services = self::getServicesByZoneIds(session('zoneIds', []))?->where('type', ServiceTypeEnum::FIXED)?->get();
        }

        if (is_array($ids)) {
            if (count($ids)) {
                $services = self::getServicesByIds($ids);
            }
        }

        $paginate = self::getThemeOptionsPaginate();
        return $services?->paginate($paginate['service_per_page']);
    }

    public static function getCategories($ids = [])
    {
        $categories = collect();
        if (count(session('zoneIds', []))) {
            $categories = self::getCategoriesByZoneIds(session('zoneIds', []))?->get();
        }

        if (is_array($ids)) {
            if (count($ids)) {
                $categories = self::getCategoriesByIds($ids);
            }
        }

        $paginate = self::getThemeOptionsPaginate();
        return $categories?->paginate($paginate['categories_per_page']);
    }

    public static function getBookingsCountById($id)
    {
        $role = self::getRoleByUserId($id);
        $bookings = 0;

        if ($role == RoleEnum::PROVIDER) {
            $bookings = Booking::whereNull('parent_id')
                ->whereHas('sub_bookings', function ($query) use ($id) {
                    $query->where('provider_id', $id);
                })->count();
        } elseif ($role == RoleEnum::CONSUMER) {
            $bookings = Booking::whereNull('parent_id')
                ->whereHas('sub_bookings', function ($query) use ($id) {
                    $query->where('consumer_id', $id);
                })->count();
        } elseif ($role == RoleEnum::SERVICEMAN) {
            $bookings = Booking::whereHas('servicemen', function ($query)  use ($id) {
                $query->where('users.id', $id);
            })->whereNotNull('parent_id')
                ->count();
        }

        return $bookings;
    }

    public static function getLanguageByLocale($locale)
    {
        return SystemLang::where('locale', $locale)?->whereNull('deleted_at')->first();
    }

    public static function getServicemenCountById($id)
    {
        $role = self::getRoleByUserId($id);

        if ($role == RoleEnum::PROVIDER) {
            $servicemen = User::role(RoleEnum::SERVICEMAN)->where('system_reserve', false)->where('provider_id', $id)->count();
        }
        return $servicemen ?? 0;
    }

    public static function getServicesCountById($id)
    {

        $role = self::getRoleByUserId($id);

        if ($role == RoleEnum::PROVIDER) {
            $services = Service::where('user_id', $id);
        }
        return $services?->count() ?? 0;
    }

    public static function getBalanceById($id)
    {

        $role = self::getRoleByUserId($id);
        if ($role == RoleEnum::PROVIDER) {
            $provider = User::findOrFail($id);
            $balance = $provider?->providerWallet?->balance;
        } elseif ($role == RoleEnum::SERVICEMAN) {
            $servicemen = User::findOrFail($id);

            $balance = $servicemen?->servicemanWallet?->balance;;
        } elseif ($role == RoleEnum::CONSUMER) {
            $consumer = User::findOrFail($id);
            $balance = $consumer?->wallet?->balance;;
        }
        return $balance ?? 0.0;
    }

    public static function isFileExistsFromURL($url, $placeHolder = false)
    {
        if(!is_null($url) && !empty($url)) {
            $localFilePath = public_path(self::getAssetUrl($url));
            if(file_exists($localFilePath)) {
                return asset(self::getAssetUrl($url));
            }
        }
        
        if($placeHolder) {
            return FrontEnum::getPlaceholderImageUrl();
        }

        return false;
    }

    public static function getBookingsCountByStatus($status)
    {
        $role = self::getCurrentRoleName();
        $providerId = null;
        $servicemanId = null;

        if ($role == RoleEnum::PROVIDER) {
            $providerId = auth()?->user()?->id;
        } elseif ($role == RoleEnum::SERVICEMAN) {
            $servicemanId = auth()?->user()?->id;
        }
        $bookings = Booking::getFilteredBookings($providerId,$servicemanId);
        
        $bookingCount = $bookings->filter(function ($booking) use ($status) {
            return $booking->booking_status?->slug === $status;
        })->count();


        return $bookingCount;
    }

}
