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
}
