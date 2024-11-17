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
        Schema::create('connectors', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('campaign_id')->unsigned()->index()->nullable();
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            $table->bigInteger('process_id')->unsigned()->index()->nullable();
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('cascade');
            $table->bigInteger('connector_id')->unsigned()->index()->nullable();
            $table->foreign('connector_id')->references('id')->on('connectors');
            $table->json('data')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connectors');
    }
};
