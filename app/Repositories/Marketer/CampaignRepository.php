<?php
namespace App\Repositories\Marketer;
use App\Models\Campaign;
use Auth;
use DB;

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

    public function getCampaign($item)
    {
        $data = $this->model->where('affiliate_id',Auth::user()->id)->paginate($item);

        return $data;
    }

    public function get_campaign($request)
    {
        // $last_seven_day = date('Y-m-d',strtotime("-7 days"));
        // return $last_seven_day;
        $data = $this->model->where(function($query) use ($request){
            if($request->sort_by == 'Today')
            {
                $date= date('Y-m-d');
                $query->whereDate('created_at', $date);
            }
            elseif($request->sort_by == 'Yesterday')
            {
                $yesterday = date('Y-m-d',strtotime("-1 days"));
                $query->whereDate('created_at', $yesterday);
            }
            elseif($request->sort_by == '7 Days')
            {
                $last_date = date('Y-m-d',strtotime("-7 days"));
                $today_date = date('Y-m-d');
                $query->whereBetween(DB::raw('DATE(created_at)'), [$last_date,$today_date]);
            }
            elseif($request->sort_by == '15 Days')
            {
                $last_date = date('Y-m-d',strtotime("-15 days"));
                $today_date = date('Y-m-d');
                $query->whereBetween(DB::raw('DATE(created_at)'), [$last_date,$today_date]);
            }
            elseif($request->sort_by == '30 Days')
            {
                $last_date = date('Y-m-d',strtotime("-30 days"));
                $today_date = date('Y-m-d');
                $query->whereBetween(DB::raw('DATE(created_at)'), [$last_date,$today_date]);
            }
            elseif($request->sort_by == '60 Days')
            {
                $last_date = date('Y-m-d',strtotime("-60 days"));
                $today_date = date('Y-m-d');
                $query->whereBetween(DB::raw('DATE(created_at)'), [$last_date,$today_date]);
            }
            elseif($request->sort_by == '180 Days')
            {
                $last_date = date('Y-m-d',strtotime("-180 days"));
                $today_date = date('Y-m-d');
                $query->whereBetween(DB::raw('DATE(created_at)'), [$last_date,$today_date]);
            }
            elseif($request->sort_by == '1 Year')
            {
                $last_date = date('Y-m-d',strtotime("-365 days"));
                $today_date = date('Y-m-d');
                $query->whereBetween(DB::raw('DATE(created_at)'), [$last_date,$today_date]);
            }
        })->get();

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