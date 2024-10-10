<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enterprise;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    public function getAll(Request $request, UserRepository $userRepository)
    {
        $request->validate([
            'enterprise_id' => 'required|string'
        ]);

        $enterprise = Enterprise::find($request->input('enterprise_id'));

        if (!$enterprise) {
            return response()->json(['message' => 'Enterprise not found'], 404);
        } else {
            $users = $userRepository->getAllUsers($request->enterprise_id);
            return response()->json(['users' => $users]);
        }
    }
    public function deleteUser(Request $request, UserRepository $userRepository)
    {
        $request->validate([
            'enterprise_id' => 'required|string',
            'user_id' => 'required|string'
        ]);

        $enterprise = Enterprise::find($request->input('enterprise_id'));

        if (!$enterprise) {
            return response()->json(['message' => 'Enterprise not found'], 404);
        } else {
            $result = $userRepository->delete($request->input('enterprise_id'), $request->input('user_id'));
            if ($result) {
                return response()->json(['message' => 'Funcionário deletado com sucesso']);
            }
        }
    }
    public function updateUser(Request $request, UserRepository $userRepository)
    {
        $request->validate([
            'enterprise_id' => 'required|string',
            'user_id' => 'required|string',
            'name' => 'required|string|min:3',
            'email' => 'required|email'
        ]);

        $enterprise = Enterprise::find($request->input('enterprise_id'));

        if (!$enterprise) {
            return response()->json(['message' => 'Enterprise not found'], 404);
        } else {
            $result = $userRepository->update(
                $request->input('enterprise_id'),
                $request->input('user_id'),
                $request->input('name'),
                $request->input('email')
            );
            if ($result) {
                $users = $userRepository->getAllUsers($request->enterprise_id);
                return response()->json(['users' => $users, 'message' => 'Funcionário atualizado com sucesso']);
            } else {
                return response()->json(['message' => 'Erro ao atualizar funcionário']);
            }
        }
    }
}
