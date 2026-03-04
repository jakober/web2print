<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFonts extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        /*
        Schema::create('fonts', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('family');
            $table->string('weight');
            $table->string('variant');
            $table->string('filename');
            $table->unique(array('family','weight','variant'));
        });
         *
         */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        /*
        Schema::drop('fonts');
         */
    }

}
