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
        Schema::create('contact_email', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            // Foreign keys
            $table->integer('contact_id')->unsigned();

            $table->timestamps();

            // Relationships
            $table->foreign('contact_id')->references('id')->on('contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_email');
    }
};
