<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0030 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('admins', function($table) {
            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->string('username')->length(32);
            $table->string('password')->length(60);
            $table->integer('gruppe');

            //$table->string('email')->length(80);

            $table->timestamps();

            //$table->unique(array('mandant_id', 'email'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('admins');
    }

}
