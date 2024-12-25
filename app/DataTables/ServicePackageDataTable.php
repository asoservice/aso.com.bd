<?php

namespace App\DataTables;

use App\Helpers\Helpers;
use App\Models\ServicePackage;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Services\DataTable;

class ServicePackageDataTable extends DataTable
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
            ->editColumn('checkbox', function ($row) {
                return '<div class="form-check"><input type="checkbox" name="row" class="rowClass form-check-input" value='.$row->id.' id="rowId'.$row->id.'"></div>';
            })
            ->editColumn('title', function ($row) {
                $media = $row->getFirstMedia('image');
                $imageUrl = $media ? $media->getUrl() : asset('admin/images/No-image-found.jpg');
                $imageTag = '<img src="'.$imageUrl.'" alt="Image" class="img-thumbnail img-fix">';
                $price = $row->price ? Helpers::getSettings()['general']['default_currency']->symbol . number_format($row->price, 2) : 'N/A';
    
                return '
                <div class="service-list-item">
                    '.$imageTag.'
                    <div class="details">
                        <h5 class="mb-0">'.$row->title.'</h5>
                        <div class="info">
                            <span>Price: '.$price.'</span>
                        </div>
                    </div>
                </div>
            ';
            
            })
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
            ->editColumn('started_at', function ($row) {
                if (empty($row->started_at) && empty($row->ended_at)) {
                    return '<i data-feather=""></i>'; 
                }
                return $row->started_at . ' to ' . $row->ended_at;
            }) 
            ->editColumn('price', function ($row) use ($currencySymbol) {
                return $currencySymbol.''.$row->price;
            })
            ->editColumn('Image', function ($row) {
                $media = $row->getFirstMedia('image');
                if ($media) {
                    return '<img src="'.$media->getUrl().'" alt="Image" class="img-thumbnail img-fix">';
                }

                return '<img src="'.asset('admin/images/No-image-found.jpg').'" alt="Placeholder Image" class="img-thumbnail img-fix">';
            })
            ->editColumn('action', function ($row) {
                return view('backend.inc.action', [
                    'edit' => 'backend.service-package.edit',
                    'delete' => 'backend.service-package.destroy',
                    'data' => $row,
                ]);
            })
            ->editColumn('status', function ($row) {
                return view('backend.inc.action', [
                    'toggle' => $row,
                    'name' => 'status',
                    'route' => 'backend.service-package-status',
                    'value' => $row->status,
                ]);
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->diffForHumans();
            })
            ->rawColumns(['checkbox', 'title', 'Image', 'action', 'created_at', 'status']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ServicePackage $model): QueryBuilder
    {
        $servicePackages = $model->newQuery();
        if (request()->order) {
            if ((bool) head(request()->order)['column']) {
                $index = head(request()->order)['column'];
                if (! isset(request()->columns[$index]['orderable'])) {
                    return $servicePackages;
                }
            }
        }

        return $servicePackages->orderBy('created_at', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $user = auth()->user();
        $builder = $this->builder();
        $no_records_found = __('static.no_records_found');

        $builder->setTableId('servicepackage-table');
        if ($user->can('backend.service-package.destroy')) {
            $builder->addColumn(['data' => 'checkbox', 'title' => '<div class="form-check"><input type="checkbox" class="form-check-input" title="Select All" id="select-all-rows" /> </div>', 'class' => 'title', 'orderable' => false, 'searchable' => false]);
        }
            $builder
            ->addColumn(['data' => 'title', 'title' => __('static.name'), 'orderable' => true, 'searchable' => true])
            ->addColumn(['data' => 'user.name', 'title' => __('static.service.provider_name'), 'orderable' => true, 'searchable' => true])
            ->addColumn(['data' => 'started_at', 'title' => __('static.coupon.validity'), 'orderable' => false, 'searchable' => false])
            ->addColumn(['data' => 'created_at', 'title' => __('static.created_at'), 'orderable' => true, 'searchable' => false]);

            if ($user->can('backend.service-package.edit') || $user->can('backend.service-package.destroy')) {
                if ($user->can('backend.service-package.edit')) {
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
        return 'ServicePackage_'.date('YmdHis');
    }
}
