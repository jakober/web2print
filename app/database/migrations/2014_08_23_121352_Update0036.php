<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0036 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('lieferscheine', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->date('liefertermin');
        });

        Schema::create('lieferscheine_orders', function($table) {
            $table->engine = 'InnoDB';
            $table->integer('lieferschein_id')->unsigned();
            $table->integer('order_id')->unsigned();
            $table->foreign('lieferschein_id')->references('id')->on('lieferscheine');
            $table->foreign('order_id')->references('id')->on('orders');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('lieferscheine_orders');
        Schema::drop('lieferscheine');
    }

}
