<?php

namespace App\Repositories;

use App\Models\Enterprise;
use App\Models\Prospect;
use Illuminate\Support\Str;

class ProspectRepository extends AbstractRepository
{

    protected $model = Prospect::class;

    public function getAllProspects($enterprise_id)
    {
        return $this->model::getProspectsByEnterpriseId($enterprise_id);
    }
    public function createProspect($user_id, $client_id, $car_id, $interest, $enterprise_id)
    {
        $user = Enterprise::find($enterprise_id);
        if (!$user) {
            throw new \Exception('Empresa não encontrada');
        }

        $this->model::create([
            'id' => Str::uuid(),
            'user_id' => $user_id,
            'client_id' => $client_id,
            'car_id' => $car_id,
            'interest' => $interest,
            'enterprise_id' => $enterprise_id,
        ]);

        return $this->getAllProspects($enterprise_id);
    }
    public function update($id, $user_id, $client_id, $car_id, $interest, $enterprise_id)
    {
        $prospect = $this->model::find($id);
        if (!$prospect) {
            throw new \Exception('Prospecção não encontrada');
        }

        $prospect->update([
            'user_id' => $user_id,
            'client_id' => $client_id,
            'car_id' => $car_id,
            'interest' => $interest,
            'enterprise_id' => $enterprise_id
        ]);

        return $this->getAllProspects($enterprise_id);
    }
    public function delete($enterprise_id, $prospect_id)
    {
        $prospects = $this->model::getProspectsByEnterpriseId($enterprise_id);

        $prospectToDelete = $prospects->where('id', $prospect_id)->first();

        if (!$prospectToDelete) {
            throw new \Exception('Prospecção não encontrado');
        }

        $prospectToDelete->delete();

        return true;
    }
}
