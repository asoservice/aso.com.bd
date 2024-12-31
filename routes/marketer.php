<?php
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\Marketer\{AffiliateController};

Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard','Marketer\AffiliateController@index')->name('affiliate.dashboard');
    
    Route::get('generate_affiliate_link','Marketer\AffiliateController@generate_affiliate_link')->name('affiliate.generate_affiliate_link');
    Route::get('campaigns','Marketer\AffiliateController@campaigns')->name('affiliate.campaigns');
    Route::get('service_affiliate_links','Marketer\AffiliateController@service_affiliate_links')->name('affiliate.service_affiliate_links');
    Route::get('provider_affiliate_links','Marketer\AffiliateController@provider_affiliate_links')->name('affiliate.provider_affiliate_links');
    Route::get('banner_creatives','Marketer\AffiliateController@banner_creatives')->name('affiliate.banner_creatives');
    Route::get('marketing_resources','Marketer\AffiliateController@marketing_resources')->name('affiliate.marketing_resources');
    Route::get('marketing_guidelines','Marketer\AffiliateController@marketing_guidelines')->name('affiliate.marketing_guidelines');
});
