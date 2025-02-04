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
        $table->integer('ticket_count')->default(1)->change(); // Set default 1 atau nilai lain
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->integer('ticket_count')->nullable(false)->change(); // Kembalikan ke non-nullable
    });
}
};
