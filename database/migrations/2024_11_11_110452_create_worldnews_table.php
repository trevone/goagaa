<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('worldnews', function (Blueprint $table) {
            $table->id();
            $table->string('worldnews_id')->unique()->index()->nullable();
            $table->string('title');
            $table->text('text');
            $table->text('summary')->nullable();
            $table->string('url');
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->date('publish_date'); 
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worldnews');
    }
};
