<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->index(['available', 'sort', 'id'], 'products_available_sort_id_index');
            $table->index(['available', 'category_id', 'sort', 'id'], 'products_available_category_sort_id_index');
        });

        Schema::table('categories', function (Blueprint $table): void {
            $table->index(['sort', 'id'], 'categories_sort_id_index');
        });

        Schema::table('testimonials', function (Blueprint $table): void {
            $table->index(['available', 'sort', 'id'], 'testimonials_available_sort_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->dropIndex('products_available_sort_id_index');
            $table->dropIndex('products_available_category_sort_id_index');
        });

        Schema::table('categories', function (Blueprint $table): void {
            $table->dropIndex('categories_sort_id_index');
        });

        Schema::table('testimonials', function (Blueprint $table): void {
            $table->dropIndex('testimonials_available_sort_id_index');
        });
    }
};
