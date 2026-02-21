<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['todo', 'in_progress', 'review', 'done', 'cancelled'])->default('todo');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('creator_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('tasks')->nullOnDelete()->comment('Подзадача');
            $table->date('due_date')->nullable();
            $table->integer('estimated_hours')->nullable()->comment('Оценка в часах');
            $table->integer('actual_hours')->nullable()->comment('Фактически потрачено');
            $table->integer('position')->default(0)->comment('Порядок в колонке');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['project_id', 'status']);
            $table->index(['assignee_id', 'status']);
            $table->index(['due_date', 'status']);
        });

        // Теги задач
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color', 7)->default('#6B7280');
            $table->timestamps();
        });

        Schema::create('task_tag', function (Blueprint $table) {
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['task_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_tag');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('tasks');
    }
};
