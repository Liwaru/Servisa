<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users') || Schema::hasColumn('users', 'id_user')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('no_hp')->nullable()->unique();
            $table->string('username')->nullable()->unique();
            $table->unsignedTinyInteger('level')->default(1);
            $table->string('status_akun')->default('aktif');
        });

        DB::table('users')->orderBy('id')->each(function (object $user): void {
            DB::table('users')->where('id', $user->id)->update([
                'no_hp' => 'legacy-'.$user->id,
                'username' => isset($user->email) ? strstr($user->email, '@', true) : 'user'.$user->id,
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('id', 'id_user');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasColumn('users', 'id_user')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('id_user', 'id');
            $table->dropUnique(['no_hp']);
            $table->dropUnique(['username']);
            $table->dropColumn(['no_hp', 'username', 'level', 'status_akun']);
        });
    }
};
