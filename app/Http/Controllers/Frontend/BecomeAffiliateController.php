<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class BecomeAffiliateController extends Controller
{
    public function index()
    {
        return view('frontend.become-affiliate.index');
    }

    public function join_affiliate($id)
    {
        $user = User::find($id);

        $user->assignRole('Marketer');

        return redirect(route('affiliate.dashboard'));
    }
}
