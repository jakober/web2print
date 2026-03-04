<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0018 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('user_vorlagen', function($table) {
                    $table->engine = 'InnoDB';
                    $table->increments('id');
                    $table->integer('user_id')->unsigned();
                    $table->integer('vorlage_id')->unsigned();
                    $table->timestamps();

                    $table->unique(array('user_id', 'vorlage_id'));

                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                    $table->foreign('vorlage_id')->references('id')->on('vorlagen')->onDelete('cascade');
                });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('user_vorlagen');
    }

}
