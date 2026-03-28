<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('company');
            $table->string('source_name')->nullable();
            $table->string('source_url')->nullable();
            $table->string('apply_url')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('salary_expectation', 10, 2)->nullable();
            $table->enum('status', [
                'identified',
                'applied',
                'recruiter_interview',
                'technical_interview',
                'technical_test',
                'offer',
                'hired',
                'rejected',
                'ghosted',
            ])->default('identified');
            $table->text('notes')->nullable();
            $table->date('applied_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
