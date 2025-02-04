<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPriceColumnInConcertsTable extends Migration
{
    public function up()
    {
        Schema::table('concerts', function (Blueprint $table) {
            // Ubah tipe data kolom price menjadi lebih besar
            $table->decimal('price', 10, 2)->change();

            // Tambahkan kolom is_promotion untuk menandakan apakah konser sedang promo
            $table->boolean('is_promotion')->default(false);

            // Tambahkan kolom promotion_price untuk harga diskon jika ada promosi
            $table->decimal('promotion_price', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('concerts', function (Blueprint $table) {
            // Kembalikan tipe data kolom price ke nilai sebelumnya
            $table->decimal('price', 8, 2)->change();

            // Hapus kolom is_promotion dan promotion_price jika rollback
            $table->dropColumn('is_promotion');
            $table->dropColumn('promotion_price');
        });
    }
}
