<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use App\Models\Enterprise;

class AuthController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'As credenciais fornecidas estão incorretas.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'enterprise_id' => 'required|string'
        ]);

        $enterprise = Enterprise::find($request->input('enterprise_id'));

        if (!$enterprise) {
            return response()->json(['error' => 'Enterprise not found'], 404);
        }

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'enterprise_id' => $enterprise->id,
        ]);

        $users = User::getUsersByEnterpriseId($enterprise->id);

        return response()->json(['users' => $users, 'message' => 'Funcionário cadastrado com sucesso']);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Link de reset de senha enviado com sucesso']);
        } else {
            return response()->json(['error' => 'Erro ao enviar link de reset de senha'], 422);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->setRememberToken(Str::random(60));

                $user->save();

                return response()->json(['message' => 'Senha resetada com sucesso']);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Senha resetada com sucesso']);
        } else {
            return response()->json(['error' => 'Erro ao resetar senha'], 422);
        }
    }

    public function getUser(Request $request)
    {
        return response()->json($request->user());
    }
}
