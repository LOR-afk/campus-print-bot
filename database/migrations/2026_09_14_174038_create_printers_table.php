<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();

            $table->enum('status', [
                'available',
                'unavailable',
                'maintenance',
            ])->default('available');

            $table->enum('paper_status', [
                'available',
                'low',
                'empty',
            ])->default('available');

            $table->enum('toner_status', [
                'good',
                'low',
                'empty',
            ])->default('good');

            $table->unsignedInteger('queue_count')->default(0);
            $table->unsignedInteger('estimated_wait_minutes')->default(0);

            $table->string('issue_reason')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('printers');
    }
};