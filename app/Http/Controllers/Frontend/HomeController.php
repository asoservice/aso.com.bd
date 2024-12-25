<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('frontend.home.index', [
            'SEOData' => new SEOData(
                title: 'Awesome News - My Project',
                description: 'Lorem Ipsum',
            ),
        ]);
    }
}
