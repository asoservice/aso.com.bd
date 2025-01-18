<?php

namespace App\Http\Controllers\Test;

use App\Helpers\ContentsLoader;
use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TestController extends Controller
{
    private $app;

    public function __construct()
    {
        $this->app = new  ContentsLoader;
        $this->app->routeName('t', '')->addViews('test')->setModel(FaqCategory::class);
    }
    /**
     * Display a listing of the resource.
     */
    /* public function index()
    {
        if (request()->ajax()) {
            $roles = FaqCategory::query();

            return DataTables::of($roles)
                ->addColumn('action', function ($row) {
                     return 'Action';
                })
                ->addColumn('checkbox', function ($row) {
                    if ($row->system_reserve) {
                        return '<div class="form-check">
                                    <input type="checkbox" class="form-check-input" disabled>
                                </div>';
                    }

                    return '<div class="form-check">
                                <input type="checkbox" name="row" class="rowClass form-check-input" value="' . $row->id . '" id="rowId' . $row->id . '">
                            </div>';
                })
                ->editColumn('name', function ($row) {
                    return $row->name;
                })
                ->editColumn('created_at', function ($row) {
                    return date('d-M-Y', strtotime($row->created_at));
                })
                ->editColumn('updated_at', function ($row) {
                    return $row->updated_at->diffForHumans();
                })
                ->rawColumns(['action', 'checkbox'])
                ->make(true);
        }

        return view('test.index'); // Ensure the view exists
        /* $this->app->findAndSetData(5);
        $blogs = DB::table('blogs')->latest('id')->get(['id', 'title']);
        return $this->app
            ->addInput('Name', 'name', value: 'SYED AMIR')
            ->addInput('Email', 'email')
            ->addInput('Description', 'description', 'description')
            ->addFileInput('Profile Picture', 'profile_picture', required: true)
            ->addSwitchInput('Status', 'status', 'on')
            ->addSelectInput('Select Blog', 'blog_id', function($o) use ($blogs) {$o->data = $blogs; return $o;})
            ->createPage(); * /
    } */

    public function index()
    {
        return $this->app
            ->initDataTable()
            ->addCheckBoxColumn()
            ->addImageColumn('Image', 'icon')
            ->addColumn('Name', 'name')
            ->addColumn('Created At', 'created_at')
            ->addColumn('Updated At', 'updated_at')
            ->addActionColum()
            ->renderDataTable();
        /* if (request()->ajax()) {
            $roles = FaqCategory::query();

            return DataTables::of($roles)
                ->addColumn('action', function ($row) {
                    return "
                        <div class='action-div'>
                            <a href='http://127.0.0.1:8000/backend/role/5/edit' class='edit-icon'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit'><path d='M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7'></path><path d='M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z'></path></svg>
                            </a>
                        
                            <a href='javascript:void(0)' class='lock-icon'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-lock'><rect x='3' y='11' width='18' height='11' rx='2' ry='2'></rect><path d='M7 11V7a5 5 0 0 1 10 0v4'></path></svg>
                            </a>

                            <a href='#confirmationModal{$row->id}' data-bs-toggle='modal' class='delete-svg'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash-2 remove-icon delete-confirmation'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg>
                            </a>
                        </div>
                    ";
                })
                ->addColumn('checkbox', function ($row) {
                    if ($row->system_reserve) {
                        return '<div class="form-check">
                                    <input type="checkbox" class="form-check-input" disabled>
                                </div>';
                    }

                    return '<div class="form-check">
                                <input type="checkbox" name="row" class="rowClass form-check-input" value="' . $row->id . '" id="rowId' . $row->id . '">
                            </div>';
                })
                ->editColumn('name', function ($row) {
                    return $row->name;
                })
                ->editColumn('created_at', function ($row) {
                    return date('d-M-Y', strtotime($row->created_at));
                })
                ->editColumn('updated_at', function ($row) {
                    return $row->updated_at->diffForHumans();
                })
                ->rawColumns(['action', 'checkbox'])
                ->make(true);
        }

        return view('test.index'); */ 
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $this->app->findAndSetData(5);
        // return $this->app->model::get();
        $blogs = DB::table('blogs')->latest('id')->get(['id', 'title']);
        return $this->app
        ->addInput('Description', 'description', 'description')
            ->addInput('Name', 'name', value: true)
            ->addInput('Email', 'email')
            // ->addInput('Description2', 'description2', 'normal')
            ->addFileInput('Profile Picture', 'profile_picture', required: true)
            ->addSwitchInput('Status', 'status', 'on')
            ->addSelectInput('Select Blog', 'blog_id', function($o) use ($blogs) {$o->data = $blogs; return $o;})
            ->formOnly();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
}
