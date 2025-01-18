<?php

namespace App\Http\Controllers\Marketer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Marketer\CampaignRepository;
use App\Http\Requests\Marketer\CampaignRequest;

class CampaignController extends Controller
{
    protected $repo;
    public function __construct(CampaignRepository $repo)
    {
        $this->repo = $repo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CampaignRequest $request)
    {
        // dd($request->all());
       return $this->repo->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function get_campaign(Request $request)
    {
        $data['my_camp'] = $this->repo->get_campaign($request);
        $data['sl'] = 1;
        return view('marketer.pages.partials.sort_campaign',compact('data'));
    }
}
