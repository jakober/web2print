<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0027 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up() {
        Schema::table('vorlagen', function($table) {
            $table->float('scale')->default(1.0)->after('seiten');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('vorlagen', function($table) {
            $table->dropColumn('scale');
        });
    }

}
