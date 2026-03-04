<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0020 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('extras', function($table) {
            $table->engine = 'InnoDB';

            $table->integer('mandant_id')->unsigned();
            $table->string('name')->length(32);
            $table->string('text')->length(32);
            $table->float('price')->default(0);
            $table->integer('sort')->default(0);

            $table->primary(array('mandant_id','name'));
            $table->foreign('mandant_id')->references('id')->on('mandanten');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('extras');
    }

}
