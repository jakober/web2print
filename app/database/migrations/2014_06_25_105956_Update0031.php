<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0031 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up() {
        Schema::table('vorlagen', function($table) {
            $table->String('template_folder')->length(80)->after('folder');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('vorlagen', function($table) {
            $table->dropColumn('template_folder');
        });
    }

}
