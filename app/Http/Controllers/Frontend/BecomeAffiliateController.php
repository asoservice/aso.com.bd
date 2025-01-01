<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class BecomeAffiliateController extends Controller
{
    public function index()
    {
        return view('frontend.become-affiliate.index');
    }

    public function joinAffiliate()
    {
        if(Auth::check())
        {
            $user = User::find(Auth::user()->id);
    
            $user->assignRole('Marketer');
    
            return redirect(route('affiliate.dashboard'));
        }
        else
        {
            return redirect(route('frontend.login.index'));
        }
    }
}
