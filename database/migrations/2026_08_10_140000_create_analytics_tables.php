<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table): void {
            $table->id();
            $table->string('visitor_hash', 64);
            $table->string('path', 255);
            $table->string('route_name', 100)->nullable();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->index(['created_at', 'visitor_hash'], 'page_views_date_visitor_index');
            $table->index(['product_id', 'created_at'], 'page_views_product_date_index');
        });

        Schema::create('analytics_events', function (Blueprint $table): void {
            $table->id();
            $table->string('event_type', 50);
            $table->string('visitor_hash', 64);
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->index(['event_type', 'created_at'], 'analytics_events_type_date_index');
            $table->index(['product_id', 'created_at'], 'analytics_events_product_date_index');
            $table->index(['visitor_hash', 'created_at'], 'analytics_events_visitor_date_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
        Schema::dropIfExists('page_views');
    }
};
