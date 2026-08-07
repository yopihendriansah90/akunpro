<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description');
            $table->unsignedInteger('price');
            $table->unsignedInteger('original_price')->nullable();
            $table->string('duration');
            $table->string('warranty');
            $table->string('icon')->default('apps');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('badge')->nullable();
            $table->boolean('available')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
