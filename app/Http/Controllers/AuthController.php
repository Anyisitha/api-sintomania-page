<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Services\{AppServices, UserServices};
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, UserServices $user, AppServices $app): JsonResponse
    {
        DB::beginTransaction();
        try {
            $exists_user = $user->validate_user($request->email);

            if (isset($exists_user->id)) {
                if (Auth::attempt(["email" => $exists_user->email, "password" => "password"])) {
                    $exists_user_token = $user->find_user($exists_user->id);
                    $auth_data = $user->login_user($exists_user_token);
                }
            } else {
                $new_user = $user->createUser($request);
                if (isset($new_user->id)) {
                    $auth_data = $user->login_user($new_user);
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollBack();
            return $app->responseApi(false, "error", "Ocurrio un problema al crear el usuario", $result);
        }

        return $app->responseApi(true, "success", "Usuario creado.", $auth_data);
    }

    public function validateToken()
    {
        return response()->json(["data" => Auth::user()],200);
    }
}
