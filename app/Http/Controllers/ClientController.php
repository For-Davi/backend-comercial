<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Repositories\ClientRepository;
use App\Models\Enterprise;

class ClientController extends Controller
{
    public function getAll(ClientRepository $clientRepository, $enteprise_id)
    {
        $clients = $clientRepository->getAllClients($enteprise_id);
        return response()->json(['clients' => $clients]);
    }

    public function createClient(Request $request, ClientRepository $clientRepository)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'user_id' => 'required|uuid'
        ]);

        $clients = $clientRepository->createClient(
            $request->input('name'),
            $request->input('email'),
            $request->input('address'),
            $request->input('phone'),
            $request->input('user_id'),
        );
        return response()->json(['clients' => $clients, 'message' => 'Cliente cadastrado com sucesso']);
    }
    public function deleteClient(Request $request, ClientRepository $clientRepository)
    {
        $request->validate([
            'enterprise_id' => 'required|string',
            'client_id' => 'required|string'
        ]);

        $enterprise = Enterprise::find($request->input('enterprise_id'));

        if (!$enterprise) {
            return response()->json(['message' => 'Enterprise not found'], 404);
        } else {
            try {
                $clientRepository->delete($request->input('enterprise_id'), $request->input('client_id'));
                return response()->json(['message' => 'Funcionário deletado com sucesso'], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Erro ao deletar funcionário'], 500);
            }
        }
    }

    public function updateClient(Request $request, ClientRepository $clientRepository)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'id' => 'required|uuid',
            'enterprise_id' => 'required|uuid'
        ]);

        $client = Client::find($request->input('id'));
        if (!$client) {
            return response()->json(['message' => 'Cliente não encontrado'], 404);
        } else {
            try {
                $clients = $clientRepository->update(
                    $request->input('name'),
                    $request->input('email'),
                    $request->input('address'),
                    $request->input('phone'),
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
