<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0033 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('druckboegen', function($table) {
            $table->float('offset_y')->after('ny');
            $table->float('offset_x')->after('ny');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('druckboegen', function($table) {
            $table->dropColumn('offset_x');
            $table->dropColumn('offset_y');
        });  //
    }

}
