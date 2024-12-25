<?php

namespace App\Repositories\Backend;

use App\Models\CommissionHistory;
use Prettus\Repository\Eloquent\BaseRepository;

class CommissionRepository extends BaseRepository
{
    public function model()
    {
        return CommissionHistory::class;
    }

    public function index()
    {
        return view('backend.commission.index');
    }

    public function create($attribute = [])
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function store($request)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update($request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
