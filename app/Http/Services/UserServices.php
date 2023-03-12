<?php

namespace App\Http\Services;

use App\Http\Requests\Auth\{RegisterRequest};
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserServices
{
    public function login_user(User $user): mixed
    {
        $credentials = [
            "email" => $user->email,
            "password" => "password"
        ];

        if (Auth::attempt($credentials)) {
            $userAuth = Auth::user();
            if ($userAuth) {
                $token = $userAuth->createToken("sintomania")->accessToken;
                return [
                    "token" => $token,
                    "user" => $user
                ];
            }
        } else {
            return response()->json("mierda fallo");
        }
    }

    public function createUser(RegisterRequest $request): User
    {
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make("password"),
            "phone" => $request->phone,
            "pharmacy_name" => $request->pharmacy_name,
            "chain" => $request->chain,
            "accepted_terms_date" => Carbon::now(),
            "role" => "Gamer"
        ]);

        return $user;
    }

    public function validate_user(string $email): User | null
    {
        $user = User::where("email", "LIKE", "%$email%")->first();
        return $user;
    }

    public function find_user(int $id): User
    {
        $user = User::find($id);
        return $user;
    }
}
