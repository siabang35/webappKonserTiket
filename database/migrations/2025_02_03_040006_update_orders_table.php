<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Cek apakah kolom 'status' belum ada sebelum menambahkannya
            if (!Schema::hasColumn('orders', 'status')) {
                $table->string('status')->default('pending');
            }

            if (!Schema::hasColumn('orders', 'ticket_code')) {
                $table->string('ticket_code')->unique()->nullable();
            }

            if (!Schema::hasColumn('orders', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('orders', 'ticket_code')) {
                $table->dropColumn('ticket_code');
            }
            if (Schema::hasColumn('orders', 'deleted_at')) {
                $table->dropColumn('deleted_at');
            }
        });
    }
};
