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
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('key', 255);
            $table->text('value')->nullable();
            $table->string('icon', 255)->nullable();
            $table->string('status', 255)->default('1');
            $table->string('type', 255)->default('detail')->nullable();
            $table->timestamps();
        });

        // Adding indexes
        Schema::table('details', function (Blueprint $table) {
            $table->index('key');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details');
    }
};
