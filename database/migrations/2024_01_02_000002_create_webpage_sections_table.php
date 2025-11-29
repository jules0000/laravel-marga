<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webpage_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webpage_id')->constrained()->onDelete('cascade');
            $table->string('type')->default('content'); // content, image, button, heading, etc.
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('image_path')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->string('button_style')->default('primary'); // primary, secondary
            $table->integer('order')->default(0);
            $table->json('metadata')->nullable(); // For additional flexible data
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webpage_sections');
    }
};

