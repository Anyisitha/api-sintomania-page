<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Services\{AppServices, UserServices};
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{Auth, DB};

class AuthController extends Controller
{
    /**
     * Registers a new user.
     * @param RegisterRequest $request The user registration request data.
     * @param UserServices $user The user service to handle user operations.
     * @param AppServices $app The application service to handle application operations.
     * @return JsonResponse Returns a JSON response with the result of the operation.
     * @throws \Throwable If an error occurs while creating the user.
     */
    public function register(RegisterRequest $request, UserServices $user, AppServices $app): JsonResponse
    {
        $status = false;
        $result = null;
        DB::beginTransaction();
        try {
            $new_user = $user->createUser($request);

            $status = false;
            DB::commit();

            return $app->responseApi(
                $status,
                "success",
                "Usuario creado exitosamente.",
                $new_user
            );
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollBack();

            return $app->responseApi(
                $status,
                "error",
                "Ocurrio un problema al momento de crear el usuario.",
                $result
            );
        }
    }

    /**
     * Logs in a user by validating credentials, returning a JSON response with a token and user information.
     * @param Request $request The HTTP request containing the user's email and password
     * @param UserServices $user An instance of the UserServices class
     * @param AppServices $app An instance of the AppServices class
     * @return JsonResponse A JSON response containing a token and user information if login is successful, or an error message if login fails
     */
    public function login(Request $request, UserServices $user, AppServices $app): JsonResponse
    {
        return $user->login_user($request, $app);
    }

    /**
     * Activates a user with the given id
     * @param string $id The id of the user to activate
     * @param AppServices $app An instance of the AppServices class
     * @param UserServices $user An instance of the UserServices class
     * @return JsonResponse Returns a JSON response with the result of the operation
     */
    public function activeUser(string $id, AppServices $app, UserServices $user): JsonResponse
    {
        $status = false;
        $result = null;
        DB::beginTransaction();
        try {
            $active_user = $user->active_user($id);

            $status = false;
            DB::commit();

            return $app->responseApi(
                true,
                "success",
                "El usuario se activo exitosamente.",
                $active_user
            );
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollBack();

            return $app->responseApi(
                $status,
                "error",
                "Ocurrio un problema al momento de activar el usuario.",
                $result
            );
        }
    }

    /**
     * Logs in an administrator user.
     * @param Request $request The login request data.
     * @param UserServices $user The user service to handle user operations.
     * @param AppServices $app The application service to handle application operations.
     * @return JsonResponse Returns a JSON response with the result of the operation.
     */
    public function login_admin(Request $request, UserServices $user, AppServices $app): JsonResponse
    {
        return $user->login_admin($request, $app);
    }

    public function validateToken()
    {
        return response()->json(["data" => Auth::user()], 200);
    }
}
