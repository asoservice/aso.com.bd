<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Service;
use App\Repositories\Backend\DashboardRepository;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $repository;

    public function __construct(DashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function permission()
    {
        $permission = DB::table('permissions')->orderBy('id','ASC')->pluck('name')->toArray();
        
        $role = Role::find(1);
        $role->syncPermissions($permission);
        
        return redirect()->back();
    }

    public function create_permission(Request $request)
    {
        // dd($request->all());
        $routeName = $request->input('route');
        $prefix = $request->input('prefix') ?? 'backend';
        $routeResources = ['index','create','store','edit','update','destroy','show'];
        if(empty($routeName)) return redirect()->back()->with('error', 'Resource route point name or route name is required !.');

        $messages = [];
        $actions = [];
        foreach ($routeResources as $routeResource) {
            if(str_ends_with($routeName, ".{$routeResource}")) {
                $strRouteResource = implode(', ', $routeResources);
                return redirect()->back()->with('error', "Route name should not end with [{$strRouteResource}]");
            }

            $action = "{$prefix}.{$routeName}.{$routeResource}";
            $actions[$routeResource] = $action;
            
            if(DB::table('permissions')->where('name', $action)->exists()) {
                $messages[$routeResource] = "Permission [{$action}] already exists.";
            } else {
                DB::table('permissions')->insert([
                    'name' => $action,
                    'guard_name' => 'web',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
    
                $messages[$routeResource] = "[{$action}] permission created successfully.";
            }
        }

        if(DB::table('modules')->where('name', $routeName)->exists()) {
            $messages['module'] = "Module [{$routeName}] already exists.";
            return redirect()->back()->with('error', $messages);
        }

        DB::table('modules')->insert([
            'name' => $routeName,
            'actions' => json_encode($actions),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $messages['module'] = "Module [{$routeName}] created successfully.";
        return redirect()->back()->with('success', $messages);


        /* $actions = '{"index":"backend.'.$request->route.'.index","create":"backend.'.$request->route.'.create","edit":"backend.'.$request->route.'.edit","destroy":"backend.'.$request->route.'.destroy"}';

        $permissions = [
            "backend.$request->route.index",
            "backend.$request->route.create",
            "backend.$request->route.edit",
            "backend.$request->route.destroy",
        ];
        // return $permissions;
        $check = DB::table('modules')->where('name',$request->route)->count();
        if($check == 0)
        {
            for ($i=0; $i < count($permissions) ; $i++) 
            { 
                DB::table('permissions')->insert([
                    'name' => $permissions[$i],
                    'guard_name' => 'web',
                ]);
            }

            DB::table('modules')->insert([
                'name'=>$request->route,
                'actions' => $actions,   
            ]);
        }
        else
        {
            return redirect()->back()->with('message', 'This Route is already taken !.');
        }

        return redirect()->back(); */
    }

    /**
     * Show Admin Dashboard
     */
    public function index(Request $request)
    {
        $providerId = null;
        $servicemanId = null;
        $services = Service::whereNull('deleted_at')
            ->having('bookings_count', '>', 0)
            ->orderByDesc('bookings_count');
        $reviews = Review::with('service')->whereNotNull('service_id');

        if (auth()->check() && Helpers::hasRole('provider')) {
            $providerId = auth()?->user()?->id;
            $services = $services->where('user_id', $providerId);
            $reviews = $reviews->where('provider_id', $providerId);
        } else if (auth()->check() && Helpers::hasRole('serviceman')) {
            $servicemanId = auth()?->user()?->id;
        }

        return view('backend.dashboard.index')->with([
            'data' => $this->chart($request),
            'fetchTopProviders' => $this->fetchTopProviders()?->paginate(5),
            'topServicemen' => $this->getTopServicemen($providerId)?->paginate(5),
            'bookings' => Booking::getFilteredBookings($providerId,$servicemanId),
            'blogs' => Blog::whereNull('deleted_at')->paginate(2),
            'services' => $services->paginate(5),
            'reviews' => $reviews->paginate(5),
        ]);
    }

    public function chart($request)
    {
        return $this->repository->chart($request);
    }

    public function getTopServicemen($providerId)
    {
        return $this->repository->getTopServicemen($providerId);
    }

    public function fetchTopProviders()
    {
        return $this->repository->getTopProviders();
    }

    public function upload(Request $request)
    {
        return $this->repository->upload($request);
    }
}
