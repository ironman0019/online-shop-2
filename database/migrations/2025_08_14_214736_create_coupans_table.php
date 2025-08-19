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
        Schema::create('coupans', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->integer('amount');
            $table->tinyInteger('amount_type')->default(0)->comment('0 => percentage, 1 => price unit');
            $table->unsignedBigInteger('discount_ceiling')->nullable();
            $table->tinyInteger('type')->default(0)->comment('0 => common (each user can use one time), 1 => private (one user can use one time)');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->tinyInteger('status')->default(0);
            $table->timestamp('start_date')->useCurrent();
            $table->timestamp('end_date')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupans');
    }
};
