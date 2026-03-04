<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0021 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('orders_extras', function($table) {
            $table->engine = 'InnoDB';

            $table->integer('mandant_id')->unsigned();
            $table->integer('order_id')->unsigned();
            $table->string('name')->length(32);
            $table->float('price')->default(0);

            $table->foreign('mandant_id')->references('id')->on('mandanten');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(array('order_id', 'name'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('orders_extras');
    }

}
