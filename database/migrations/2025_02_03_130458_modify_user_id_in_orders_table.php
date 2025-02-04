<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        // Mengubah kolom user_id menjadi tidak bisa NULL
        $table->unsignedBigInteger('user_id')->nullable(false)->change();
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        // Membalik perubahan jika migrasi di-revert
        $table->unsignedBigInteger('user_id')->nullable()->change();
    });
}

};
