<?php

namespace App\Repositories\API;

use App\Enums\RoleEnum;
use App\Exceptions\ExceptionHandler;
use App\Models\Address;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;
use Spatie\Permission\Models\Role;

class UserRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name' => 'like',
    ];

    protected $role;

    protected $address;

    public function model()
    {
        $this->address = new Address();
        $this->role = new Role();

        return User::class;
    }

    public function getAllUsers()
    {
        DB::beginTransaction();
        try {
            return $this->model->role('user')->with('addresses');
        } catch (Exception $e) {
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {

            $user = $this->model->findOrFail($id);
            if ($user->hasRole(RoleEnum::ADMIN)) {
                throw new Exception(__('static.users.reserved_user_not_deleted'), 400);
            }

            return $user->destroy($id);

        } catch (Exception $e) {

            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }
}
