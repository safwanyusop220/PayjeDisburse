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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('allocation');
            $table->foreign('allocation')->references('id')->on('allocations');
            $table->unsignedBigInteger('status')->default('1');
            $table->foreign('status')->references('id')->on('statuses')->default('1');
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('program_types');
            $table->decimal('allocation_rate', 13, 2);
            $table->string('latest_payment')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('total_month')->nullable();
            $table->string('total_year')->nullable();
            $table->string('end_date')->nullable();
            $table->integer('frequency')->nullable();
            $table->string('reject_reason')->default('-');
            $table->string('installment_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
