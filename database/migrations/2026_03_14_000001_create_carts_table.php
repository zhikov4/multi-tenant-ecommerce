<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tenant_id');
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');
            $table->string('product_store');
            $table->decimal('product_price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->timestamps();
            $table->unique(['user_id', 'tenant_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};