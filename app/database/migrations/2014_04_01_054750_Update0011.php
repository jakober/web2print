<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0011 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('lieferanschriften', function($table) {
            $table->string('abteilung')->length(80)->after('firma');
            $table->boolean('aktiv');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('lieferanschriften', function($table) {
           $table->dropColumn('aktiv');
           $table->dropColumn('abteilung');
        });
    }

}
