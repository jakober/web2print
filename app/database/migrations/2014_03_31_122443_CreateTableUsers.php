<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUsers extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('gruppen', function($table) {

            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->string('bezeichnung')->length(40);

            $table->timestamps();
        });

        Schema::create('users', function($table) {
            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->integer('mandant_id')->unsigned();
            $table->integer('gruppe_id')->unsigned()->default(1);
            $table->string('username')->length(32);
            $table->string('password')->length(60);

            //$table->string('email')->length(80);

            $table->timestamps();

            $table->unique(array('mandant_id', 'username'));
            //$table->unique(array('mandant_id', 'email'));

            $table->foreign('mandant_id')->references('id')->on('mandanten');
            $table->foreign('gruppe_id')->references('id')->on('gruppen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('users');
        Schema::drop('gruppen');
    }

}
