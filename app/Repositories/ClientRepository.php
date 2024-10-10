<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Str;

class ClientRepository extends AbstractRepository
{

    protected $model = Client::class;

    public function getAllClients($enterprise_id)
    {
        return $this->model::getClientsByEnterpriseId($enterprise_id);
    }
    public function createClient($name, $email, $address, $phone, $user_id)
    {
        $user = User::find($user_id);
        if (!$user) {
            throw new \Exception('Usuário não encontrado');
        }

        $client = $this->model::create([
            'id' => Str::uuid(),
            'name' => $name,
            'email' => $email,
            'address' => $address,
            'phone' => (string) $phone,
        ]);
        $client->employees()->attach($user_id);

        return $this->getAllClients($user->enterprise_id);
    }
    public function update($name, $email, $address, $phone, $client_id, $enterprise_id)
    {
        $client = $this->model::find($client_id);
        if (!$client) {
            throw new \Exception('Cliente não encontrado');
        }

        $client->update([
            'name' => $name,
            'email' => $email,
            'address' => $address,
            'phone' => (string) $phone,
        ]);

        return $this->getAllClients($enterprise_id);
    }
    public function delete($enterprise_id, $client_id)
    {
        $users = $this->model::getClientsByEnterpriseId($enterprise_id);

        $userToDelete = $users->where('id', $client_id)->first();

        if (!$userToDelete) {
            throw new \Exception('Usuário não encontrado');
        }

        $userToDelete->load('employees');

        $employees = $userToDelete->employees;
        foreach ($employees as $employee) {
            $employee->clients()->detach($userToDelete->id);
        }

        $userToDelete->delete();

        return true;
    }
}
