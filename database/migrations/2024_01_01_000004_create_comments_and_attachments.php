<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('body');
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('original_name');
            $table->string('path');
            $table->string('mime_type');
            $table->unsignedBigInteger('size')->comment('Размер в байтах');
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event')->comment('created, updated, status_changed, etc.');
            $table->text('description');
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->json('properties')->nullable()->comment('Старые/новые значения');
            $table->timestamps();

            $table->index(['task_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('comments');
    }
};
