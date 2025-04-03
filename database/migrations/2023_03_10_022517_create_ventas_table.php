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
    Schema::create('venta_producto', function (Blueprint $table) {
        $table->id();
        $table->foreignId('venta_id')->constrained()->onDelete('cascade');
        $table->foreignId('producto_id')->constrained();
        $table->integer('cantidad');
        $table->decimal('precio_venta', 10, 2);
        $table->decimal('descuento', 10, 2)->default(0);
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
        Schema::dropIfExists('ventas');
    }
};
