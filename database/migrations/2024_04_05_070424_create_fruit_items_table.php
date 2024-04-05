<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fruit_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('category_id');
            $table->string('name', 100);
            $table->unsignedDecimal('price', 12, 2)->nullable()->default(0); 
            $table->enum('unit', ['kg', 'pcs', 'pack']);   
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fruit_items');
    }
};
