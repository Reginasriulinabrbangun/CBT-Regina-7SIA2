<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropColumn(['kelas_id', 'nis']);
            $table->string('kelas')->nullable()->after('email');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('nisn')->nullable()->unique()->after('email');
            $table->string('email')->nullable()->change();
        });

        Schema::dropIfExists('kelas');
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nisn');
            $table->string('email')->nullable(false)->change();
        });

        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn('kelas');
            $table->string('nis')->nullable();
            $table->foreignId('kelas_id')->nullable();
        });

        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->unsignedTinyInteger('tingkat')->nullable();
            $table->string('jurusan')->nullable();
            $table->timestamps();
        });
    }
};
