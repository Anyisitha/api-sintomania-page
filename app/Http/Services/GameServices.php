<?php

namespace App\Http\Services;

use App\Models\ScoreUser;
use App\Models\UserLevel;
use Illuminate\Http\JsonResponse;

class GameServices {
    public function save_score(int $user_id, string $score, string $level): ScoreUser
    {
        $score = ScoreUser::create([
            "user_id" => $user_id,
            "score" => $score,
            "level" => $level
        ]);

        return $score;
    }

    public function save_finished_level(int $user_id, string $level): UserLevel
    {
        $user = UserLevel::where("user_id", $user_id)->first();

        if(isset($user->id)) {
            $score = UserLevel::where("user_id", $user_id)
                              ->first();
            $score->level = $level;
            $score->status = "Finished";
            $score->save();
    
            return $score;
        } else {
            $score = UserLevel::create([
                "user_id" => $user_id,
                "status" => "Finalizado",
                "level" => $level
            ]);
    
            return $score;
        }
        
    }
}