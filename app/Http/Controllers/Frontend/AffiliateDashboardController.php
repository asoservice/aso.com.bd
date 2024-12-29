<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AffiliateDashboardController extends Controller
{
    public function dashboard()
    {
        return view('frontend.affiliate.dashboard');
    }
}
