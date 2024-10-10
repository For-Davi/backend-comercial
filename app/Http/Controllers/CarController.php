<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Repositories\ClientRepository;
use App\Models\Enterprise;
use App\Repositories\CarRepository;

class CarController extends Controller
{
    public function getAll(CarRepository $carRepository, $enteprise_id)
    {
        $cars = $carRepository->getAllCars($enteprise_id);
        return response()->json(['cars' => $cars]);
    }

    public function createCar(Request $request, CarRepository $carRepository)
    {
        $request->validate([
            'mark' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'price' => 'required|integer|min:0',
            'enterprise_id' => 'required|string',
        ]);

        $enterprise = Enterprise::find($request->input('enterprise_id'));

        if (!$enterprise) {
            return response()->json(['message' => 'Enterprise not found'], 404);
        } else {
            $cars = $carRepository->createCar(
                $request->input('mark'),
                $request->input('model'),
                $request->input('year'),
                $request->input('price'),
                $request->input('enterprise_id'),
            );
            return response()->json(['cars' => $cars, 'message' => 'Carro cadastrado com sucesso']);
        }
    }

    public function deleteCar(Request $request, CarRepository $carRepository)
    {
        $request->validate([
            'enterprise_id' => 'required|string',
            'car_id' => 'required|string'
        ]);

        $enterprise = Enterprise::find($request->input('enterprise_id'));

        if (!$enterprise) {
            return response()->json(['message' => 'Enterprise not found'], 404);
        } else {
            try {
                $carRepository->delete($request->input('enterprise_id'), $request->input('car_id'));
                return response()->json(['message' => 'Carro deletado com sucesso'], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Erro ao deletar carro'], 500);
            }
        }
    }

    public function updateCar(Request $request, CarRepository $carRepository)
    {
        $request->validate([
            'id' => 'required|string',
            'mark' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'price' => 'required|integer|min:0',
            'enterprise_id' => 'required|string',
        ]);

        $car = Car::find($request->input('id'));
        if (!$car) {
            return response()->json(['message' => 'Carro não encontrado'], 404);
        } else {
            try {
                $clients = $carRepository->update(
                    $request->input('mark'),
                    $request->input('model'),
                    $request->input('year'),
                    $request->input('price'),
                    $request->input('id'),
                    $request->input('enterprise_id'),
                );
                return response()->json(['clients' => $clients, 'message' => 'Cliente atualizado com sucesso']);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Erro ao atualizar cliente'], 500);
            }
        }
    }
}
