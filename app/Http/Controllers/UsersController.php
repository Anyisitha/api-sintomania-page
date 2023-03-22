<?php

namespace App\Http\Controllers;

use App\Http\Services\{UserServices, AppServices};
use Illuminate\Http\{JsonResponse, Request};

class UsersController extends Controller
{
    public function getInactiveUsers(UserServices $user, AppServices $app): JsonResponse
    {
        $users = $user->find_inactive_users();

        return $app->responseApi(
            true,
            "success",
            "Done.",
            $users
        );
    }

    public function getScoresByLevel(string $level, UserServices $user, AppServices $app): JsonResponse
    {
        $users = $user->get_scores($level);

        return $app->responseApi(
            true,
            "success",
            "Done.",
            $users
        );
    }

    public function getUsersFinishedLevel(string $level, UserServices $user, AppServices $app): JsonResponse
    {
        $users = $user->get_finished_level($level);

        return $app->responseApi(
            true,
            "success",
            "Done.",
            $users
        );
    }
}
