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
        Schema::create('fund_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fund_id')->constrained()->onDelete('cascade');
            $table->date('return_date');
            $table->enum('frequency', ['monthly', 'quarterly', 'yearly']);
            $table->decimal('percentage', 5, 2);
            $table->boolean('is_compound')->default(true);
            $table->boolean('is_reverted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fund_returns');
    }
};
