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
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('tags');
            $table->string('slug')->unique()->nullable();
            $table->text('image')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('product_categories')->onDelete('cascade')->onUpdate('cascade');
            $table->tinyInteger('show_in_menu')->default(0)->comment('0 => show , 1 => not show');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
