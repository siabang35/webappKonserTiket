<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOrdersTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menambahkan foreign key pada concert_id yang merujuk ke id di tabel concerts
            $table->foreign('concert_id')
                  ->references('id')
                  ->on('concerts')
                  ->onDelete('cascade');  // Jika konser dihapus, maka order terkait juga dihapus

            // Menambahkan foreign key pada user_id yang merujuk ke id di tabel users
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');  // Jika user dihapus, maka order terkait juga dihapus
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
            // Menghapus foreign key
            $table->dropForeign(['concert_id']);
            $table->dropForeign(['user_id']);
        });
    }
}
