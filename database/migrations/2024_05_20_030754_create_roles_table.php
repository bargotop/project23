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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Добавить роли по умолчанию
        DB::table('roles')->insert([
            ['name' => 'admin'],
            ['name' => 'teacher'],
        ]);

        // Получить идентификаторы ролей
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        $teacherRoleId = DB::table('roles')->where('name', 'teacher')->value('id');

        // Добавить поле role_id в таблицу users
        Schema::table('users', function (Blueprint $table) use ($teacherRoleId) {
            $table->unsignedBigInteger('role_id')->default($teacherRoleId); // Установите значение по умолчанию
        });

        // Обновить существующие записи в таблице users для установки значения role_id
        DB::table('users')->whereNull('role_id')->update(['role_id' => $adminRoleId]);

        // Добавить внешний ключ с onDelete('set null')
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });

        Schema::dropIfExists('roles');
    }
};
