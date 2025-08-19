<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Models\U_user;
use App\Http\Requests\U_userStoreRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class U_userController extends Controller
{
    use ApiResponse;

    public function store(U_userStoreRequest $request)
    {
        $userData = $request->only([
            'genero',
            'nombre',
            'foto',
            'apellidos',
            'fecha_nacimiento',
            'direccion',
            'nro_documento',
            'celular',
            'email',
        ]);
        $userData['estado'] = $request->input('estado', UserStatus::ACTIVE->value);
        $userData['password'] = bcrypt($request->password);

        $user = U_user::create($userData);
        $token = $user->createToken('api-token')->plainTextToken;
        $user->makeHidden(['password']);

        $user->save();
        return $this->successResponse([
            'user' => $user,
            'token' => $token,
        ], "Usuario creado correctamente");
    }
    public function me(Request $request)
    {
        $user = $request->user();
        return $this->successResponse($user, "Información del usuario");
    }
}
