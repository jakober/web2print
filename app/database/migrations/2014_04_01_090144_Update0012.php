<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0012 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('stueckzahlen', function($table) {
            $table->engine = 'InnoDB';
            $table->integer('mandant_id')->unsigned();
            $table->integer('menge')->unsigned();
            $table->float('preis');
            $table->unique(array('mandant_id', 'menge'));

            $table->foreign('mandant_id')->references('id')->on('mandanten');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('stueckzahlen');
    }

}
