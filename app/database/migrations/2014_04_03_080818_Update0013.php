<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0013 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('mandanten', function($table) {
            $table->string('email')->length(80);
        });

        Schema::table('orders', function($table) {
            $table->dropColumn('data');
            $table->dropColumn('texts');
            $table->dropColumn('qrcodes');
            $table->dropColumn('status');
            $table->String('ref')->length(32)->after('menge');

            $table->text('output')->after('anschrift_id');
            $table->text('input')->after('anschrift_id');
            $table->string('email')->length(80)->after('anschrift_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('orders', function($table) {
            $table->dropColumn('email');
            $table->dropColumn('input');
            $table->dropColumn('output');
            $table->dropColumn('ref');
            $table->text('data')->after('anschrift_id');
            $table->text('texts')->after('data');
            $table->text('qrcodes')->after('texts');
            $table->integer('status')->unsigned()->default(0)->after('menge');
        });

        Schema::table('mandanten', function($table) {
            $table->dropColumn('email');
        });
    }

}
