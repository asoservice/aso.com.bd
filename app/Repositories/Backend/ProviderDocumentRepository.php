<?php

namespace App\Repositories\Backend;

use App\Models\Document;
use App\Models\UserDocument;
use Exception;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class ProviderDocumentRepository extends BaseRepository
{
    protected $document;

    public function model()
    {
        $this->document = new Document();

        return UserDocument::class;
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $document = $this->model->create($request->except(['_token', 'submit', 'image']));

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $document->addMediaFromRequest('image')->toMediaCollection('provider_documents');
            }
            DB::commit();

            return redirect()->route('backend.provider-document.index')->with('message', 'Document Created Successfully.');
        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $userDocument = $this->model->findOrFail($id);
            $userDocument->update($request->except(['_token', 'submit', 'image']));

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $userDocument->clearMediaCollection('provider_documents');
                $userDocument->addMediaFromRequest('image')->toMediaCollection('provider_documents');
            }

            DB::commit();

            return redirect()->route('backend.provider-document.index')->with('message', 'Document Updated Successfully');
        } catch (Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $userDocument = $this->model->findOrFail($id);
            $userDocument->destroy($id);

            DB::commit();

            return redirect()->route('backend.provider-document.index')->with('message', 'Document Deleted Successfully');
        } catch (Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function deleteRows($request)
    {
        try {
            foreach ($request->id as $row => $key) {
                $providerDocument = $this->model->findOrFail($request->id[$row]);
                $providerDocument->delete();
            }
        } catch (\Exception $e) {
            redirect()->back()->with('error', $e->getMessage());
        }
    }
}
