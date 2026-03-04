<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0023 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('orders', function($table) {
            $table->string('anschrift_ort')->length(255)->nullable()->after('anschrift_id');
            $table->string('anschrift_plz')->length(5)->nullable()->after('anschrift_id');
            $table->string('anschrift_strasse')->length(255)->nullable()->after('anschrift_id');
            $table->string('anschrift_name')->length(255)->nullable()->after('anschrift_id');
            $table->string('anschrift_abteilung')->length(80)->nullable()->after('anschrift_id');
            $table->string('anschrift_firma')->length(255)->nullable()->after('anschrift_id');
            $table->float('preis')->after('menge');
        });
        Schema::table('lieferanschriften', function($table) {
            $table->string('name')->nullable()->after('abteilung');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('orders', function($table) {
            $table->dropColumn('anschrift_ort');
            $table->dropColumn('anschrift_plz');
            $table->dropColumn('anschrift_strasse');
            $table->dropColumn('anschrift_name');
            $table->dropColumn('anschrift_abteilung');
            $table->dropColumn('anschrift_firma');
            $table->dropColumn('preis');
        });
        Schema::table('lieferanschriften', function($table) {
            $table->dropColumn('name');
        });
    }

}
