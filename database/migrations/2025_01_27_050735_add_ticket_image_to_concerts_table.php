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
    Schema::table('concerts', function (Blueprint $table) {
        $table->string('ticket_image')->nullable()->after('image_url');
    });
}

public function down()
{
    Schema::table('concerts', function (Blueprint $table) {
        $table->dropColumn('ticket_image');
    });
}

};
