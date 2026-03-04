<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0035 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('mandanten_extras', function($table) {

            $table->engine = 'InnoDB';

            $table->integer('mandant_id')->unsigned();

            $table->string('key')->length(40);
            $table->text('value');

            $table->unique(array('mandant_id','key'));
            $table->foreign('mandant_id')->references('id')->on('mandanten');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('mandanten_extras');
    }

}
