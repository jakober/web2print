<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0034 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('druckboegen', function($table) {
            $table->integer('gruppe_id')->unsigned()->nullable()->after('papier');
            $table->foreign('gruppe_id')->references('id')->on('druckboegen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('druckboegen', function($table) {
            $table->dropForeign('druckboegen_gruppe_id_foreign');
            $table->dropColumn('gruppe_id');
        });
    }

}
