<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0029 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('users', function($table) {
            $table->boolean('sendmail')->after('password')->default(false);
        });

        Schema::table('mandanten', function($table) {
            $table->string('email_name')->length(80)->after('css')->nullable();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('mandanten', function($table) {
            $table->dropColumn('email_name');
        });

        Schema::table('users', function($table) {
            $table->dropColumn('sendmail');
        });
    }

}
