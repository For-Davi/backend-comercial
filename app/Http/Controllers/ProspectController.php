<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enterprise;
use App\Models\Prospect;
use App\Repositories\CarRepository;
use App\Repositories\ClientRepository;
use App\Repositories\ProspectRepository;
use App\Repositories\UserRepository;

class ProspectController extends Controller
{
    public function getAll(ProspectRepository $prospectRepository, ClientRepository $clientRepository, UserRepository $userRepository, CarRepository $carRepository, $enterprise_id)
    {
        $cars = $carRepository->getAllCars($enterprise_id);
        $clients = $clientRepository->getAllClients($enterprise_id);
        $users = $userRepository->getAllUsers($enterprise_id);
        $prospects = $prospectRepository->getAllProspects($enterprise_id);

        return response()->json(['cars' => $cars, 'clients' => $clients, 'users' => $users, 'prospects' => $prospects]);
    }

    public function createProspect(Request $request, ProspectRepository $prospectRepository, ClientRepository $clientRepository, UserRepository $userRepository, CarRepository $carRepository)
    {
        $request->validate([
            'user_id' => 'required|string',
            'client_id' => 'required|string',
            'car_id' => 'required|string',
            'interest' => 'required|numeric',
            'enterprise_id' => 'required|string',
        ]);

        $enterprise = Enterprise::find($request->input('enterprise_id'));

        if (!$enterprise) {
            return response()->json(['message' => 'Enterprise not found'], 404);
        } else {
            $prospects = $prospectRepository->createProspect(
                $request->input('user_id'),
                $request->input('client_id'),
                $request->input('car_id'),
                $request->input('interest'),
                $request->input('enterprise_id'),
            );
            $clients = $clientRepository->getAllClients($request->input('enterprise_id'));
            $users = $userRepository->getAllUsers($request->input('enterprise_id'));
            $cars = $carRepository->getAllCars($request->input('enterprise_id'));
            return response()->json(['cars' => $cars, 'clients' => $clients, 'users' => $users, 'prospects' => $prospects, 'message' => 'Prospecção cadastrada com sucesso']);
        }
    }

    public function deleteProspect(Request $request, ProspectRepository $prospectRepository)
    {
        $request->validate([
            'enterprise_id' => 'required|string',
            'prospect_id' => 'required|string'
        ]);

        $enterprise = Enterprise::find($request->input('enterprise_id'));

        if (!$enterprise) {
            return response()->json(['message' => 'Empresa não encontrada'], 404);
        } else {
            try {
                $prospectRepository->delete($request->input('enterprise_id'), $request->input('prospect_id'));
                return response()->json(['message' => 'Prospecção deletada com sucesso'], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Erro ao deletar prospecção'], 500);
            }
        }
    }

    public function updateProspect(Request $request, ProspectRepository $prospectRepository, ClientRepository $clientRepository, UserRepository $userRepository, CarRepository $carRepository)
    {
        $request->validate([
            'id' => 'required|string',
            'user_id' => 'required|string',
            'client_id' => 'required|string',
            'car_id' => 'required|string',
            'interest' => 'required|numeric',
            'enterprise_id' => 'required|string',
        ]);

        $car = Prospect::find($request->input('id'));
        if (!$car) {
            return response()->json(['message' => 'Prospecção não encontrada'], 404);
        } else {
            try {
                $prospects = $prospectRepository->update(
                    $request->input('id'),
                    $request->input('user_id'),
                    $request->input('client_id'),
                    $request->input('car_id'),
                    $request->input('interest'),
                    $request->input('enterprise_id'),
                );
                $clients = $clientRepository->getAllClients($request->input('enterprise_id'));
                $users = $userRepository->getAllUsers($request->input('enterprise_id'));
                $cars = $carRepository->getAllCars($request->input('enterprise_id'));
                return response()->json(['cars' => $cars, 'clients' => $clients, 'users' => $users, 'prospects' => $prospects, 'message' => 'Prospecção atualizada com sucesso']);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Erro ao atualizar Prospecção'], 500);
            }
        }
    }
}
