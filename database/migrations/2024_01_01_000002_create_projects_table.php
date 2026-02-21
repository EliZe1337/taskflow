<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#3B82F6')->comment('HEX цвет');
            $table->enum('status', ['active', 'on_hold', 'completed', 'archived'])->default('active');
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->foreignId('owner_id')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'owner_id']);
        });

        // Участники проекта
        Schema::create('project_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['manager', 'developer', 'viewer'])->default('developer');
            $table->timestamps();

            $table->unique(['project_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_user');
        Schema::dropIfExists('projects');
    }
};
