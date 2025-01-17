<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\WithdrawRequestDataTable;
use App\Enums\RoleEnum;
use App\Exceptions\ExceptionHandler;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CreateWithdrawRequest;
use App\Http\Requests\Backend\UpdateWithdrawRequest;
use App\Models\WithdrawRequest;
use App\Repositories\Backend\WithdrawRequestRepository;
use Exception;

class WithdrawRequestController extends Controller
{
    public $repository;

    public function __construct(WithdrawRequestRepository $repository)
    {
        $this->authorizeResource(WithdrawRequest::class, 'withdraw_request', [
            'except' => 'destroy',
        ]);

        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(WithdrawRequestDataTable $dateTable)
    {
        try {
            return $dateTable->render('backend.withdraw-request.index');

        } catch (Exception $e) {

            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
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
    public function store(CreateWithdrawRequest $request)
    {
        return $this->repository->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(WithdrawRequest $withdrawRequest)
    {
        return $this->repository->show($withdrawRequest->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WithdrawRequest $withdrawRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWithdrawRequest $request, WithdrawRequest $withdrawRequest)
    {
        return $this->repository->update($request->all(), $withdrawRequest->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WithdrawRequest $withdrawRequest)
    {
        //
    }

    public function filter($withdrawRequest, $request)
    {
        $roleName = Helpers::getCurrentRoleName();
        if ($roleName == RoleEnum::PROVIDER) {
            $withdrawRequest = $this->repository->where('provider_id', Helpers::getCurrentUserId());
        }

        if ($request->field && $request->sort) {
            $withdrawRequest = $withdrawRequest->orderBy($request->field, $request->sort);
        }

        if ($request->start_date && $request->end_date) {
            $withdrawRequest = $withdrawRequest->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        return $withdrawRequest;
    }
}
