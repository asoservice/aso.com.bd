<?php

namespace App\Repositories\Backend;

use App\Enums\RoleEnum;
use App\Helpers\Helpers;
use App\Models\Address;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Prettus\Repository\Eloquent\BaseRepository;
use Spatie\Permission\Models\Role;

class UserRepository extends BaseRepository
{
    protected $role;

    protected $address;

    public function model()
    {
        $this->address = new Address();
        $this->role = new Role();

        return User::class;
    }

    public function index()
    {
        return view('backend.user.index', ['users' => $this->model->get()]);
    }

    public function create($attribute = [])
    {
        return view('backend.user.create', [
            'roles' => $this->role->where('system_reserve', 0)->pluck('name', 'id'),
            'countries' => Helpers::getCountries(),
            'countryCodes' => Helpers::getCountryCodes(),
        ]);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $user = $this->model->create([
                'name' => $request->name,
                'email' => $request->email,
                'code' => $request->code,
                'phone' => (string) $request->phone,
                'status' => $request->status,
                'password' => Hash::make($request->password),
            ]);

            if ($request->role) {
                $role = $this->role->findOrFail($request->role);
            }
            $user->assignRole($role);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $user->addMediaFromRequest('image')->toMediaCollection('image');
            }

            if (Helpers::walletIsEnable()) {
                $user->wallet()->create();
                $user->wallet;
            }

            DB::commit();
            return redirect()->route('backend.user.index')->with('message', 'User Created Successfully.');

        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = $this->model->findOrFail($id);
        return view('backend.user.edit', [
            'user' => $user,
            'roles' => $this->role->where('system_reserve', 0)->pluck('name', 'id'),
            'countries' => Helpers::getCountries(),
        ]);
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->model->findOrFail($id);
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => (string) $request->phone,
                'code' => $request->code,
                'status' => $request->status,
            ]);

            $role = $this->role->where('name', RoleEnum::CONSUMER)->first();
            if ($request['role']) {
                $role = $this->role->findOrFail($request['role']);
            }
            $user->syncRoles([$role]);

            if ($request->file('image') && $request->file('image')->isValid()) {
                $user->clearMediaCollection('image');
                $user->addMediaFromRequest('image')->toMediaCollection('image');
            }

            DB::commit();
            return redirect()->route('backend.user.index')->with('message', 'User Updated Successfully.');

        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = $this->model->findOrFail($id);
            $user->forcedelete($id);

            return redirect()->back()->with('message', 'User Deleted Successfully');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function status($id, $status)
    {
        try {

            $user = $this->model->findOrFail($id);
            $user->update(['status' => $status]);

            return json_encode(['resp' => $user]);
        } catch (Exception $e) {

            throw $e;
        }
    }

    public function updatePassword($request, $id)
    {
        try {
            $this->model->findOrFail($id)->update([
                'password' => Hash::make($request->new_password),
            ]);

            return back()->with('message', 'User Password Update Successfully.');

        } catch (Exception $e) {
            
            return back()->with('error', $e->getMessage());
        }
    }
}
