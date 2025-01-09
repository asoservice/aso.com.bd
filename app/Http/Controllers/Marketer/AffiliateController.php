<?php

namespace App\Http\Controllers\Marketer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Marketer\CampaignRepository;

class AffiliateController extends Controller
{
    protected $path,$campaign_repo;
    public function __construct()
    {   
        $this->path = 'marketer';
        $this->campaign_repo = new CampaignRepository();
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
        $data['my_camp'] = $this->campaign_repo->getCampaign();
        $data['sl'] = 1;
        return view($this->path.'.pages.campaigns',compact('data'));
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

    public function earnings()
    {
        return view($this->path.'.pages.earnings');
    }
    public function payments()
    {
        return view($this->path.'.pages.payments');
    }
    public function comission_rate()
    {
        return view($this->path.'.pages.comission_rate');
    }
    public function affiliate_faq()
    {
        return view($this->path.'.pages.affiliate_faq');
    }
    public function affiliate_agreement()
    {
        return view($this->path.'.pages.affiliate_agreement');
    }
    public function vedio_tutorial()
    {
        return view($this->path.'.pages.vedio_tutorial');
    }
    public function support_faq()
    {
        return view($this->path.'.pages.support_faq');
    }
    public function contact()
    {
        return view($this->path.'.pages.contact');
    }
    public function live_chat()
    {
        return view($this->path.'.pages.live_chat');
    }
    public function support_ticket()
    {
        return view($this->path.'.pages.support_ticket');
    }
    public function setting()
    {
        return view($this->path.'.pages.setting');
    }
}
