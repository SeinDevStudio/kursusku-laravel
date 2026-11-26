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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->nullable()->unique()->after('name');
            $table->enum('role', ['admin', 'user'])->default('user')->after('password');
            $table->string('phone', 20)->nullable()->after('role');
            $table->string('profile_photo')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('profile_photo');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn([
                'username',
                'role',
                'phone',
                'profile_photo',
                'is_active',
                'last_login_at',
            ]);
        });
    }
};
