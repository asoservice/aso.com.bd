<?php

namespace App\DataTables;

use App\Enums\ServiceTypeEnum;
use App\Helpers\Helpers;
use App\Models\Service;
use App\Models\Category;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Services\DataTable;
use App\Enums\CategoryType;

class ServiceDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $currencySymbol = Helpers::getSettings()['general']['default_currency']->symbol;

        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->editColumn('user.name', function ($row) {
                $user = $row->user;
                if ($user) {
                    return view('backend.inc.action', [
                        'info' => $user,
                        'ratings' => $row->user->review_ratings,
                        'route' => 'backend.provider.general-info'
                    ]);
                }
                return ''; 
            })
            ->editColumn('title', function ($row) {
                $titleLink = '<a href="'.route('backend.service.edit', $row->id).'" class="text-decoration-none">'.$row->title.'</a>';
                $media = $row->getFirstMedia('image');
                $imageUrl = $media ? $media->getUrl() : asset('admin/images/No-image-found.jpg');
                $imageTag = '<img src="'.$imageUrl.'" alt="Image" class="img-thumbnail img-fix">';
            
                $price = $row->price ? '$' . number_format($row->price, 2) : '';
                $taxName = $row->tax->name ? $row->tax->name : '';
                $tax = $row->tax->rate ? $row->tax->rate . '%' : '';
                $duration = $row->duration ? $row->duration . ' ' . ($row->duration_unit ?? '') : '';
                $serviceman = $row->required_servicemen ?? '';
            
                return '
                    <div class="service-list-item">
                        '.$imageTag.'
                        <div class="details">
                            <h5 class="mb-0">'.$titleLink.'</h5>
                            <div class="info">
                                <span>Price: '.$price.'</span>
                                <span>Servicemen: '.$serviceman.'</span>
                                <span>Duration: '.$duration.'</span>
                            </div>
                        </div>
                    </div>
                ';
            })
            
            ->editColumn('type', function ($row) {
                return ucwords(str_replace('_', ' ', $row->type));
            })
            ->editColumn('type', function ($row) {
                return ucwords(str_replace('_', ' ', $row->type));
            })
            ->editColumn('status', function ($row) {
                return view('backend.inc.action', [
                    'toggle' => $row,
                    'name' => 'status',
                    'route' => 'backend.service.status',
                    'value' => $row->status,
                ]);
            })
            ->editColumn('created_at', function ($row) {
                return date('d-M-Y', strtotime($row->created_at));
            })
            ->editColumn('services.categories', function ($row) {
                $categories = $row->categories->take(2)->pluck('title')->toArray();

                return view('backend.inc.action',
                    ['categories' => $categories]  
                );
            })
            ->editColumn('action', function ($row) {
                return view('backend.inc.action', [
                    'edit' => 'backend.service.edit',
                    'delete' => 'backend.service.destroy',
                    'data' => $row,
                ]);
            })
            ->editColumn('checkbox', function ($row) {
                if ($row->first() == 'Admin') {
                    return '<div class="form-check"><input type="checkbox" class="form-check-input" id="disable-select" disabled></div>';
                }

                return '<div class="form-check"><input type="checkbox" name="row" class="rowClass form-check-input" value='.$row->id.' id="rowId'.$row->id.'"></div>';
            })
            ->rawColumns(['checkbox', 'created_at', 'status', 'title', 'type']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Service $model): QueryBuilder
    {
        
        if (auth()->user()->hasRole('provider')) {
            $services = $model->newQuery()->where('user_id', auth()->user()->id)->whereNull('parent_id')->with('user');
        } else {
            $services = $model->newQuery()->whereNull('parent_id')->with('user');
        }
        if(request()->zone){
            $zoneId = Zone::where('name',request()->zone)->pluck('id')->toArray();
            $CategoryIds = Category::with('zones')
                ->where('category_type', CategoryType::SERVICE)
                ->whereHas('zones', function ($zones) use ($zoneId) {
                    $zones->whereIn('zone_id', $zoneId);
                })->pluck('id')->toArray();

            $services = $services->whereHas('categories', function ($categories) use ($CategoryIds) {
                $categories->whereIn('category_id', $CategoryIds);
            });
        }

        if (request()->order) {
            if ((bool) head(request()->order)['column']) {
                $index = head(request()->order)['column'];
                if (! isset(request()->columns[$index]['orderable'])) {
                    return $services;
                }
            }
        }
        return $services->orderBy('created_at', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {       
        $user = auth()->user();
        $builder = $this->builder();
        $no_records_found = __('static.no_records_found');


        $builder->setTableId('service-table');

        if ($user->can('backend.service.destroy')) {
            $builder->addColumn(['data' => 'checkbox', 'title' => '<div class="form-check"><input type="checkbox" class="form-check-input" title="Select All" id="select-all-rows" /> </div>', 'class' => 'title', 'orderable' => false, 'searchable' => false]);
        }

        $builder
            ->addColumn(['data' => 'title', 'title' => __('static.name'), 'orderable' => true, 'searchable' => true])
            ->addColumn(['data' => 'user.name', 'title' => __('static.service.provider_name'), 'orderable' => true, 'searchable' => true])
            ->addColumn(['data' => 'type', 'title' => __('static.service.type'), 'orderable' => true, 'searchable' => true])
            ->addColumn(['data' => 'created_at', 'title' => __('static.created_at'), 'orderable' => true, 'searchable' => true])
            ->addColumn(['data' => 'services.categories', 'title' => __('static.service.category'), 'orderable' => false, 'searchable' => false]);
            
        if ($user->can('backend.service.edit') || $user->can('backend.service.destroy')) {
            if ($user->can('backend.service.edit')) {
                $builder->addColumn(['data' => 'status', 'title' => __('static.status'), 'orderable' => true, 'searchable' => false]);
            }
            $builder->addColumn(['data' => 'action', 'title' => __('static.action'), 'orderable' => false, 'searchable' => false]);

        }

        return $builder->minifiedAjax()
        ->selectStyleSingle()   
        ->parameters([
            'language' => [
                'emptyTable' => $no_records_found,
                'infoEmpty' => '',
                'zeroRecords' => $no_records_found,
            ],
            'drawCallback' => 'function(settings) {
                if (settings._iRecordsDisplay === 0) {
                    $(settings.nTableWrapper).find(".dataTables_paginate").hide();
                } else {
                    $(settings.nTableWrapper).find(".dataTables_paginate").show();
                }
                feather.replace();
            }',
        ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Service_'.date('YmdHis');
    }
}