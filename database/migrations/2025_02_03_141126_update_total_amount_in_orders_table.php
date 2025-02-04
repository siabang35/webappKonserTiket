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
        $table->decimal('total_amount', 10, 2)->default(0)->change(); // Set default 0 atau nilai lain
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->decimal('total_amount', 10, 2)->nullable(false)->change(); // Kembalikan ke non-nullable
    });
}

};
