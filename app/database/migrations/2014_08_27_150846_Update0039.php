<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0039 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up() {
        Schema::table('mandanten', function($table) {
            $table->string('freitext_feld1_titel')->length(80)->nullable();
            $table->string('freitext_feld1')->length(80)->nullable();
        });
        Schema::table('orders', function($table) {
            $table->string('freitext1')->length(80)->after('name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('orders', function($table) {
            $table->dropColumn('freitext1');
        });
        Schema::table('mandanten', function($table) {
            $table->dropColumn('freitext_feld1');
            $table->dropColumn('freitext_feld1_titel');
        });
    }

}
