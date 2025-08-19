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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('address_id')->nullable()->constrained('addresses')->onDelete('cascade')->onUpdate('cascade');
            $table->longText('address_object')->nullable();
            $table->foreignId('peyment_id')->nullable()->constrained('peyments')->onDelete('cascade')->onUpdate('cascade');
            $table->longText('peyment_object')->nullable();
            $table->tinyInteger('peyment_type')->default(0);
            $table->tinyInteger('peyment_status')->default(0);
            $table->foreignId('delivery_id')->nullable()->constrained('delivery')->onDelete('cascade')->onUpdate('cascade');
            $table->longText('delivery_object')->nullable();
            $table->decimal('delivery_amount', 20, 3)->nullable();
            $table->tinyInteger('delivery_status')->default(0);
            $table->timestamp('delivery_date')->nullable();
            $table->decimal('order_final_amount', 20, 3)->nullable();
            $table->decimal('order_discount_amount', 20, 3)->nullable();
            $table->foreignId('coupan_id')->nullable()->constrained('coupans')->onDelete('cascade')->onUpdate('cascade');
            $table->longText('coupan_object')->nullable();
            $table->decimal('order_coupan_discount_amount', 20, 3)->nullable();
            $table->foreignId('common_discount_id')->nullable()->constrained('common_discounts')->onDelete('cascade')->onUpdate('cascade');
            $table->longText('common_discount_object')->nullable();
            $table->decimal('order_common_discount_amount', 20, 3)->nullable();
            $table->decimal('order_total_products_discount_amount', 20, 3)->nullable();
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
        Schema::dropIfExists('orders');
    }
};
