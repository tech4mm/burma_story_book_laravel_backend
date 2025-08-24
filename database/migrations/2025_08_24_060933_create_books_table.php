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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            // Files are handled by Spatie Media Library collections
            // cover: single image
            // book: PDF file
            // description: optional image
            // $this->addMediaCollection('cover')->singleFile();
            // $this->addMediaCollection('book')->singleFile();
            // $this->addMediaCollection('description_image')->singleFile();
            $table->text('description');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->foreignId('author_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->boolean('is_featured')->default(false);
            $table->integer('views')->default(0);
            $table->integer('pages')->nullable();
            $table->boolean('is_free')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
