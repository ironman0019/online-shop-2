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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('introduction')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->text('image');
            $table->decimal('weight', 10, 2);
            $table->decimal('length', 10, 1)->comment('cm unit');
            $table->decimal('width', 10, 1)->comment('cm unit');
            $table->decimal('height', 10, 1)->comment('cm unit');
            $table->decimal('price', 20, 3);
            $table->string('tags');
            $table->foreignId('category_id')->constrained('product_categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('sold_number')->default(0);
            $table->unsignedInteger('frozen_number')->default(0);
            $table->unsignedInteger('marketable_number')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('marketable')->default(1)->comment('1 => marketable, 0 => is not marketable');
            $table->timestamp('published_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
