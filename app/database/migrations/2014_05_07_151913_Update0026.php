<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0026 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('vorlagen_lieferanschriften', function($table) {
            $table->engine = 'InnoDB';

            $table->integer('mandant_id')->unsigned();
            $table->integer('vorlage_id')->unsigned();
            $table->integer('anschrift_id')->unsigned();

            $table->foreign('mandant_id')->references('id')->on('mandanten');
            $table->foreign('vorlage_id')->references('id')->on('vorlagen');
            $table->foreign('anschrift_id')->references('id')->on('lieferanschriften');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('vorlagen_lieferanschriften');
    }

}
