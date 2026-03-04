<?php

use Illuminate\Database\Migrations\Migration;

class CreateTableMandanten extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('mandanten', function($table) {
            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->string('name')->length(80);
            $table->string('logo')->length(80);
            $table->string('hostname');
            $table->string('color')->length(40)->default(null);
            $table->timestamps();
        });

        Schema::create('vorlagen', function($table) {
            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->integer('mandant_id')->unsigned();
            $table->string('name')->length(80);
            $table->string('pdf')->length(80);
            $table->string('svg')->length(80);
            $table->float('width');
            $table->float('height');
            $table->integer('seiten')->unsigned()->default(1);

            $table->timestamps();
            $table->foreign('mandant_id')->references('id')->on('mandanten');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('vorlagen');
        Schema::drop('mandanten');
    }

}
