<?php

namespace App\Http\Controllers\Marketer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{
    protected $path;
    public function __construct()
    {   
        $this->path = 'marketer';
    }
    public function index()
    {
        return view($this->path.'.dashboard.index');
    }

    public function generate_affiliate_link()
    {
        return view($this->path.'.pages.generate_affiliate_link');
    }

    public function campaigns()
    {
        return view($this->path.'.pages.campaigns');
    }
    public function service_affiliate_links()
    {
        return view($this->path.'.pages.service_affiliate_links');
    }
    public function provider_affiliate_links()
    {
        return view($this->path.'.pages.provider_affiliate_links');
    }
    public function banner_creatives()
    {
        return view($this->path.'.pages.banner_creatives');
    }
    public function marketing_resources()
    {
        return view($this->path.'.pages.marketing_resources');
    }
    public function marketing_guidelines()
    {
        return view($this->path.'.pages.marketing_guidelines');
    }
    public function customer_affiliate()
    {
        return view($this->path.'.pages.customer_affiliate');
    }
    public function provider_affiliate()
    {
        return view($this->path.'.pages.provider_affiliate');
    }
    public function downline_marketer()
    {
        return view($this->path.'.pages.downline_marketer');
    }
    public function order_comm_reports()
    {
        return view($this->path.'.pages.order_comm_reports');
    }
    public function campaign_reports()
    {
        return view($this->path.'.pages.campaign_reports');
    }
    public function referrals_history()
    {
        return view($this->path.'.pages.referrals_history');
    }
}
