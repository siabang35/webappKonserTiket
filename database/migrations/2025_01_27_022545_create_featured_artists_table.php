<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturedArtistsTable extends Migration
{
    public function up()
    {
        Schema::create('featured_artists', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama artis
            $table->string('genre'); // Genre musik
            $table->string('image')->nullable(); // URL atau path gambar
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('featured_artists');
    }
}
