<?php

namespace App\Http\Services;

use App\Http\Requests\Auth\{RegisterRequest};
use App\Models\ScoreUser;
use App\Models\User;
use App\Models\UserLevel;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};
use Illuminate\Pagination\LengthAwarePaginator;

class UserServices
{
    /**
     * Logs in a user with given credentials and returns a response with an access token and user details.
     * @param RegisterRequest $user The user credentials to log in.
     * @param AppServices $appServices The application services instance.
     * @return mixed Returns a response with an access token and user details if the user is logged in successfully
     */
    public function login_user(Request $user, AppServices $appServices): mixed
    {
        $user_gamer = User::where("email", $user->email)
            ->where("role", "Gamer")
            ->first();

        if (isset($user_gamer->id)) {
            $credentials = $user->only("email", "password");

            if (Auth::attempt($credentials)) {
                $get_user = User::where("email", $user->email)
                    ->where("status", "Activo")
                    ->first();

                if (isset($get_user->id)) {
                    $token = $get_user->createToken("Sintomania")->accessToken;

                    return $appServices->responseApi(
                        true,
                        "success",
                        "Bienvenido otra vez!",
                        array(
                            "token" => $token,
                            "user" => $get_user
                        )
                    );
                } else {
                    return $appServices->responseApi(
                        false,
                        "error",
                        "El usuario no esta autorizado para el ingreso",
                        array()
                    );
                }
            } else {
                return $appServices->responseApi(
                    false,
                    "error",
                    "El usuario no existe.",
                    array()
                );
            }
        } else {
            return $appServices->responseApi(
                false,
                "error",
                "El usuario no tiene rol de gamer.",
                array()
            );
        }
    }

    public function login_admin(Request $request, AppServices $app): JsonResponse
    {
        $is_admin = User::where("email", "LIKE", $request->email)
            ->where("role", "Admin")
            ->first();

        if (isset($is_admin->id)) {
            if (Auth::attempt($request->only("email", "password"))) {
                $user = Auth::user();
                $exists_user = User::find($user->id);

                $token = $exists_user->createToken("Sintomania Admin")->accessToken;

                return $app->responseApi(
                    true,
                    "success",
                    "Bienvenido Administrador",
                    array("token" => $token, "user" => $user)
                );
            } else {
                return $app->responseApi(
                    false,
                    "error",
                    "El usuario ingresado no existe.",
                    array()
                );
            }
        } else {
            return $app->responseApi(
                false,
                "error",
                "El usuario ingresado no es administrador.",
                array()
            );
        }
    }

    /**
     * Find a user by email.
     *
     * @param  string  $email  The email of the user to find.
     * @return array  An array containing the transaction status, a message, and the user if found.
     */
    public function find_user_by_email(string $email): array
    {
        $user = User::where("email", "LIKE", $email)->first();

        if (isset($user->id)) {
            return array(
                "transaction" => array("status" => true),
                "message" => array("type" => "success", "message" => "Done."),
                $user
            );
        } else {
            return array(
                "transaction" => array("status" => false),
                "message" => array("type" => "error", "message" => "El usuario no existe."),
                array()
            );
        }
    }

    /**
     * Create a new user.
     *
     * @param  \Illuminate\Http\Request  $request  The registration request.
     * @return \App\Models\User  The newly created user.
     */
    public function createUser(RegisterRequest $request): User
    {
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make("password"),
            "phone" => $request->phone,
            "pharmacy_name" => $request->pharmacy_name,
            "chain" => $request->chain,
            "status" => "Inactivo",
            "accepted_terms_date" => Carbon::now(),
            "role" => "Gamer"
        ]);

        return $user;
    }

    /**
     * Validate a user by email.
     *
     * @param  string  $email  The email of the user to validate.
     * @return \App\Models\User|null  The user validated, or null if not found.
     */
    public function validate_user(string $email): User | null
    {
        $user = User::where("email", "LIKE", "%$email%")->first();
        return $user;
    }

    /**
     * Find a user by ID.
     *
     * @param  int  $id  The ID of the user to find.
     * @return \App\Models\User  The user found.
     */
    public function find_user(int $id): User
    {
        $user = User::find($id);
        return $user;
    }

    /**
     * Activate a user.
     *
     * @param  string  $id  The ID of the user to activate.
     * @return \App\Models\User  The activated user.
     */
    public function active_user(string $id): User
    {
        $user = User::find($id);
        $user->status = "Activo";
        $user->save();

        return $user;
    }

    /**
     * Find inactive users.
     * @return \Illuminate\Pagination\LengthAwarePaginator The paginated list of inactive users.
     */
    public function find_inactive_users(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return User::where("role", "Gamer")
            ->where("status", "Inactivo")
            ->paginate(10);
    }

    public function get_scores(string $level): LengthAwarePaginator
    {
        $scores = ScoreUser::with("user")->where("level", $level)->orderBy("score", "DESC")->paginate(10);

        return $scores;
    }

    public function get_finished_level(string $level): LengthAwarePaginator
    {
        $scores = UserLevel::with("user")->where("level", $level)->orderBy("id", "DESC")->paginate(10);

        return $scores;
    }
}
