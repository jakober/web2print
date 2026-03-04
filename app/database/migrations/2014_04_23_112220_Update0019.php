<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0019 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('fonts', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('family')->length(80);
            $table->string('weight')->length(20);
            $table->string('style')->length(20);
            $table->string('basefilename')->length(80);
            $table->unique(array('family', 'weight', 'style'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('fonts', function($table) {
            Schema::drop('fonts');
        });
    }

}
