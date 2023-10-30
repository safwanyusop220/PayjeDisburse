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
        Schema::create('allocations', function (Blueprint $table) {
            $table->id();
            $table->string('allocation_source');
            $table->decimal('total_allocation');
            $table->decimal('allocation_balance')->nullable();
            $table->unsignedBigInteger('status')->default('1');
            $table->foreign('status')->references('id')->on('statuses')->default('1');
            $table->string('account_number')->nullable();
            $table->unsignedBigInteger('bank')->nullable();
            $table->foreign('bank')->references('id')->on('ref_banks')->nullable();
            $table->string('reject_reason')->default('-')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allocations');
    }
};
