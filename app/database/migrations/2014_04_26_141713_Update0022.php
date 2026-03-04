<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0022 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up() {
        Schema::table('mandanten', function($table) {
            $table->boolean('preise_verwaltung')->default(0)->after('hostname');
            $table->boolean('preise_besteller')->default(0)->after('hostname');
            $table->boolean('anschrift_manuell')->default(0)->after('hostname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('mandanten', function($table) {
            $table->dropColumn('anschrift_manuell');
            $table->dropColumn('preise_besteller');
            $table->dropColumn('preise_verwaltung');
        });
    }

}
