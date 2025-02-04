<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('concerts', function (Blueprint $table) {
            $table->text('description')->nullable()->after('price');
            $table->string('venue')->nullable()->after('location');
            $table->time('time')->nullable()->after('date');
            $table->string('genre')->nullable()->after('time');
            $table->string('image_url')->nullable()->after('genre');
            $table->integer('tickets_left')->default(100)->after('image_url');
            $table->enum('status', ['upcoming', 'limited'])->default('upcoming')->after('tickets_left');
        });
    }

    public function down(): void
    {
        Schema::table('concerts', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'venue',
                'time',
                'genre',
                'image_url',
                'tickets_left',
                'status'
            ]);
        });
    }
};
