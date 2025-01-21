<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyConcertIdColumnInOrdersTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Mengubah tipe data concert_id menjadi bigint(20) unsigned
            $table->unsignedBigInteger('concert_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menurunkan tipe data concert_id jika rollback dilakukan
            $table->bigInteger('concert_id')->change();
        });
    }
}
