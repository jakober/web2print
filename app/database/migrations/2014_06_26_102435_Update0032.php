<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0032 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('preisschema', function($table) {

            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->string('name')->length(40);
        });

        Schema::create('preisschema_preise', function($table) {
            $table->engine = 'InnoDB';
            $table->integer('preisschema_id')->unsigned();
            $table->integer('menge')->unsigned();
            $table->float('preis');
            $table->unique(array('preisschema_id', 'menge'));

            $table->foreign('preisschema_id')->references('id')->on('preisschema')->onDelete('cascade');
        });

        Schema::table('vorlagen', function($table) {
            $table->integer('preisschema_id')->unsigned()->nullable();
            $table->foreign('preisschema_id')->references('id')->on('preisschema')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('vorlagen', function($table) {
            $table->dropForeign('vorlagen_preisschema_id_foreign');
            $table->dropColumn('preisschema_id');
        });
        Schema::drop('preisschema_preise');
        Schema::drop('preisschema');
    }

}
