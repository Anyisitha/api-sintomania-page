<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreUser extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "level", "score"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
