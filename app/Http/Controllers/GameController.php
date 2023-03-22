<?php

namespace App\Http\Controllers;

use App\Http\Services\{AppServices, GameServices};
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{DB, Auth};

class GameController extends Controller
{
    /**
     * Save the score of a game.
     * @param Request $request The HTTP request.
     * @param GameServices $game The game service instance.
     * @param AppServices $app The application service instance.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the status of the operation and the result data.
     */
    public function saveScore(Request $request, GameServices $game, AppServices $app): JsonResponse
    {
        $status = false;
        $result = null;
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $get_score = $game->save_score($user->id, $request->score, $request->level);

            $status = false;
            DB::commit();

            return $app->responseApi(
                $status,
                "success",
                "Saved score",
                $get_score
            );
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollBack();

            return $app->responseApi(
                false,
                "error",
                "Ocurrio un problema al momento de guardar el score del usuario.",
                $result
            );
        }
    }

    public function saveFinishedLevel(Request $request, GameServices $game, AppServices $app): JsonResponse
    {
        $status = false;
        $result = null;
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $get_score = $game->save_finished_level($user->id, $request->level);

            $status = false;
            DB::commit();

            return $app->responseApi(
                $status,
                "success",
                "Saved score",
                $get_score
            );
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollBack();

            return $app->responseApi(
                false,
                "error",
                "Ocurrio un problema al momento de guardar el score del usuario.",
                $result
            );
        }
    }
}
