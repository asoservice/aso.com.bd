<?php
namespace App\Repositories\Marketer;
use App\Models\Campaign;
use Auth;

class CampaignRepository{
    protected $model;
    public function __construct()
    {
        $this->model = new Campaign();
    }

    public function checkUnique($campaign_name)
    {
        $check = $this->model->where('name',$campaign_name)->where('affiliate_id',Auth::user()->id)->first();
        return $check;
    }

    public function getCampaign()
    {
        $data = $this->model->where('affiliate_id',Auth::user()->id)->paginate(10);

        return $data;
    }

    public function store($request)
    {
        try {
            $check = $this->checkUnique($request->name);
            if(isset($check))
            {
                return back()->with('error','This campaign is already taken!');
            }
            $this->model->create([
                'name' => $request->name,
                'affiliate_id' => Auth::user()->id,
            ]);
            return back()->with('message', 'Campaign Created Successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}