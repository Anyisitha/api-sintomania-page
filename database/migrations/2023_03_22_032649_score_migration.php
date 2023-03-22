<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("score_users", function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->string("score");
            $table->string("level");
            $table->timestamps();

            $table->foreign("user_id")
                  ->references("id")
                  ->on("users")
                  ->onUpdate("no action")
                  ->onDelete("no action");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("score_users");
    }
};
