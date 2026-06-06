<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('status', 20)->default('active');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('admin_password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        if (Schema::hasColumn('users', 'is_admin')) {
            $adminUsers = DB::table('users')->where('is_admin', true)->get();

            foreach ($adminUsers as $row) {
                if (DB::table('admins')->where('email', $row->email)->doesntExist()) {
                    DB::table('admins')->insert([
                        'name' => $row->name,
                        'email' => $row->email,
                        'password' => $row->password,
                        'email_verified_at' => $row->email_verified_at,
                        'status' => Schema::hasColumn('users', 'status') ? ($row->status ?? 'active') : 'active',
                        'created_at' => $row->created_at,
                        'updated_at' => $row->updated_at,
                    ]);
                }
            }

            DB::table('users')->where('is_admin', true)->delete();

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_admin');
            });
        }

        if (DB::table('admins')->where('email', 'admin@lheuredevoyage.com')->doesntExist()) {
            DB::table('admins')->insert([
                'name' => 'Admin User',
                'email' => 'admin@lheuredevoyage.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_password_reset_tokens');
        Schema::dropIfExists('admins');

        if (! Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_admin')->default(false)->after('password');
            });
        }
    }
};
