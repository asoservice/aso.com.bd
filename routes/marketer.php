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

    Route::get('customer_affiliate','Marketer\AffiliateController@customer_affiliate')->name('affiliate.customer_affiliate');
    Route::get('provider_affiliate','Marketer\AffiliateController@provider_affiliate')->name('affiliate.provider_affiliate');
    Route::get('downline_marketer','Marketer\AffiliateController@downline_marketer')->name('affiliate.downline_marketer');
    Route::get('order_comm_reports','Marketer\AffiliateController@order_comm_reports')->name('affiliate.order_comm_reports');
    Route::get('campaign_reports','Marketer\AffiliateController@campaign_reports')->name('affiliate.campaign_reports');
    Route::get('referrals_history','Marketer\AffiliateController@referrals_history')->name('affiliate.referrals_history');
});
