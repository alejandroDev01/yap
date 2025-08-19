<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Http\Requests\LoginRequest;
use App\Models\U_rol;
use App\Models\U_user;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    use ApiResponse;

    public function loginWithGoogle(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string',
        ]);

        try {
            $response = Http::withOptions([
                'verify' => false, // Solo para desarrollo local
            ])->asForm()->post('https://oauth2.googleapis.com/token', [
                'code' => $data['code'],
                'client_id' => env('GOOGLE_CLIENT_ID'),
                'client_secret' => env('GOOGLE_CLIENT_SECRET'),
                'redirect_uri' => env('GOOGLE_REDIRECT_URI'),
                'grant_type' => 'authorization_code',
            ]);

            if ($response->failed()) {
                throw new \Exception('No se pudo autenticar con Google: ' . $response->body());
            }

            $tokens = $response->json();

            $userResponse = Http::withOptions([
                'verify' => false, // Solo para desarrollo local
            ])->withToken($tokens['access_token'])
                ->get('https://www.googleapis.com/oauth2/v2/userinfo');

            if ($userResponse->failed()) {
                throw new \Exception('No se pudo obtener información del usuario: ' . $response->body());
            }

            $googleUser = $userResponse->json();

            if (!isset($googleUser['email'], $googleUser['name'])) {
                throw new \Exception('Datos incompletos de Google');
            }

            if (!($googleUser['verified_email'] ?? false)) {
                throw new \Exception('El email debe estar verificado en Google');
            }

            $fullName = $googleUser['name'];
            $nameParts = explode(' ', $fullName, 2);
            $nombre = $nameParts[0];
            $apellidos = isset($nameParts[1]) ? $nameParts[1] : '';

            return $this->successResponse([
                'email' => $googleUser['email'],
                'nombre_completo' => $googleUser['name'],
                'nombre_separado' => $nombre,
                'apellidos_separado' => $apellidos,
                'foto' => $googleUser['picture'] ?? null,
                'email_verificado' => $googleUser['verified_email'] ?? false,
                'locale' => $googleUser['locale'] ?? null,
                'given_name' => $googleUser['given_name'] ?? null,
                'family_name' => $googleUser['family_name'] ?? null,
            ], 'Datos recuperados');
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return $this->customError('Error en la solicitud HTTP', 500, ['message' => $e->getMessage()]);
        } catch (\Exception $e) {
            return $this->customError('Error interno del servidor', 500, ['message' => $e->getMessage()]);
        }
    }

    public function login(LoginRequest $request)
    {
        $user = U_user::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->unauthorizedResponse('Credenciales inválidas');
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->successResponse([
            'user' => $user,
            'token' => $token,
        ], 'Login exitoso');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse(null, 'Sesión cerrada');
    }
}
