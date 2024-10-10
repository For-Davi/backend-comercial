<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends AbstractRepository
{

    protected $model = User::class;

    public function getAllUsers($enterprise_id)
    {
        return $this->model::getUsersByEnterpriseId($enterprise_id);
    }

    public function delete($enterprise_id, $user_id)
    {
        $users = $this->model::getUsersByEnterpriseId($enterprise_id);

        $userToDelete = $users->where('id', $user_id)->first();

        if (!$userToDelete) {
            throw new \Exception('Usuário não encontrado');
        }

        $userToDelete->delete();

        return true;
    }

    public function update($enterprise_id, $user_id, $name, $email)
    {
        $users = $this->model::getUsersByEnterpriseId($enterprise_id);

        $userToUpdate = $users->where('id', $user_id)->first();

        if (!$userToUpdate) {
            throw new \Exception('Usuário não encontrado');
        }

        $userToUpdate->update([
            'name' => $name,
            'email' => $email
        ]);

        return $userToUpdate;
    }
}
