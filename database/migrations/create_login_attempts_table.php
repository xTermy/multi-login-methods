<?php

namespace StormCode\MultiLoginMethods\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignUuid('user_id');
            $table->string('method');
            $table->string('code')->nullable();
            $table->ipAddress('ip');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on(Config::string('multiLoginMethods.auth_model_table', 'users'))->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_attempts');
    }
};
