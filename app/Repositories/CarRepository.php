<?php

namespace App\Repositories;

use App\Models\Car;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Support\Str;

class CarRepository extends AbstractRepository
{

    protected $model = Car::class;

    public function getAllCars($enterprise_id)
    {
        return $this->model::getCarsByEnterpriseId($enterprise_id);
    }
    public function createCar($mark, $model, $year, $price, $enterprise_id)
    {
        $user = Enterprise::find($enterprise_id);
        if (!$user) {
            throw new \Exception('Empresa não encontrada');
        }

        $this->model::create([
            'id' => Str::uuid(),
            'mark' => $mark,
            'model' => $model,
            'year' => $year,
            'price' => $price,
            'enterprise_id' => $enterprise_id,
        ]);

        return $this->getAllCars($enterprise_id);
    }
    public function update($mark, $model, $year, $price, $id, $enterprise_id)
    {
        $car = $this->model::find($id);
        if (!$car) {
            throw new \Exception('Carro não encontrado');
        }

        $car->update([
            'mark' => $mark,
            'model' => $model,
            'year' => $year,
            'price' => $price,
        ]);

        return $this->getAllCars($enterprise_id);
    }
    public function delete($enterprise_id, $car_id)
    {
        $cars = $this->model::getCarsByEnterpriseId($enterprise_id);

        $carToDelete = $cars->where('id', $car_id)->first();

        if (!$carToDelete) {
            throw new \Exception('Carro não encontrado');
        }

        $carToDelete->delete();

        return true;
    }
}
