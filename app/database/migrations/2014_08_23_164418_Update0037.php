<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0037 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::drop('lieferscheine_orders');

        Schema::table('orders', function($table) {
            $table->integer('lieferschein_id')->unsigned()->nullable()->default(null);
            $table->foreign('lieferschein_id')->references('id')->on('lieferscheine');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {

        Schema::table('orders', function($table) {
            $table->dropForeign('orders_lieferschein_id_foreign');
            $table->dropColumn('lieferschein_id');
        });

        Schema::create('lieferscheine_orders', function($table) {
            $table->engine = 'InnoDB';
            $table->integer('lieferschein_id')->unsigned();
            $table->integer('order_id')->unsigned();
            $table->foreign('lieferschein_id')->references('id')->on('lieferscheine');
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }
}
