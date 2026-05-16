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
        Schema::table('wargas', function (Blueprint $table) {
            $table->string('jenis_hunian')->nullable()->after('no_hp');
            // Change status from enum to string to support more options
            $table->string('status')->default('aktif')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wargas', function (Blueprint $table) {
            $table->dropColumn('jenis_hunian');
            // Reverting status to enum is risky if new values exist.
            // So we leave it as string in down() or restore original if possible.
        });
    }
};
