<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\PaymentTransactions;
use App\Http\Controllers\Controller;
use App\DataTables\TransactionsDataTable;
use App\Repositories\Backend\TaxRepository;

class TransactionController extends Controller
{
    public $repository;

    public function __construct(TaxRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(TransactionsDataTable $dataTable)
    {
        return $dataTable->render('backend.transaction.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

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
    public function edit(PaymentTransactions $tax) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentTransactions $tax) {}

    public function status(Request $request, $id) {}

    public function deleteRows(Request $request) {}
}
