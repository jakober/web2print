<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0028 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('druckboegen', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name')->length(80);
            $table->string('papier')->length(80);
            $table->integer('seiten');
            $table->float('width');
            $table->float('height');
            $table->float('vk_width');
            $table->float('vk_height');
            $table->float('x0');
            $table->float('y0');
            $table->float('dx');
            $table->float('dy');
            $table->integer('nx');
            $table->integer('ny');
            $table->boolean('vordruck_1');
            $table->boolean('vordruck_2');
            $table->boolean('druck_1');
            $table->boolean('druck_2');
            $table->unique(array('name', 'seiten'));
        });

        Schema::table('vorlagen', function($table) {
            $table->integer('druckbogen_id')->unsigned()->nullable();
            $table->foreign('druckbogen_id')->references('id')->on('druckboegen')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('vorlagen', function($table) {
            $table->dropForeign('vorlagen_druckbogen_id_foreign');
            $table->dropColumn('druckbogen_id');
        });
        Schema::drop('druckboegen');
    }

}
