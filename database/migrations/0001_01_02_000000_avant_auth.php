<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->after('email', function (Blueprint $table) {
                $table->bigInteger('avant_auth_id');
                $table->longText('avant_auth_token');
                $table->longText('avant_auth_refresh_token');
            });

            $table->dropColumn('email_verified_at');
            $table->dropColumn('password');
            $table->dropColumn('remember_token');
        });

        Schema::dropIfExists('password_reset_tokens');
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->after('email', function (Blueprint $table) {
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
            });

            $table->dropColumn('avant_auth_id');
            $table->dropColumn('avant_auth_token');
            $table->dropColumn('avant_auth_refresh_token');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }
};
