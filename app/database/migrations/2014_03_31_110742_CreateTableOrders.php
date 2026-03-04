<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrders extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('status', function($table) {
            $table->engine = 'InnoDB';
            $table->integer('id')->unsigned();
            $table->primary('id');
            $table->string('bezeichnung');
            $table->string('bild')->length(20)->nullable()->default(null);
            $table->timestamps();
        });

        Schema::create('lieferanschriften', function($table) {
            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->integer('mandant_id')->unsigned();

            $table->string('firma');
            $table->string('strasse');
            $table->string('plz');
            $table->string('ort');
            $table->string('land');

            $table->foreign('mandant_id')->references('id')->on('mandanten');
        });

        Schema::create('orders', function($table) {
            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->integer('mandant_id')->unsigned();
            $table->integer('vorlage_id')->unsigned();
            $table->integer('status_id')->unsigned();
            $table->integer('anschrift_id')->unsigned()->nullable();

            $table->text('data');
            $table->text('texts');
            $table->text('qrcodes');

            $table->integer('menge')->unsigned();
            $table->integer('status')->unsigned()->default(0);

            $table->timestamps();

            $table->foreign('mandant_id')->references('id')->on('mandanten');
            $table->foreign('vorlage_id')->references('id')->on('vorlagen');
            $table->foreign('status_id')->references('id')->on('status');
            $table->foreign('anschrift_id')->references('id')->on('lieferanschriften');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('orders');
        Schema::drop('lieferanschriften');
        Schema::drop('status');
    }

}
