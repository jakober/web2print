<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update0016 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::statement('ALTER TABLE `users` MODIFY COLUMN `password` VARCHAR(100)');

        Schema::table('orders', function($table) {
            $table->string('name')->length(120)->after('status_id');
            $table->integer('user_id')->unsigned()->after('name');
        });

        DB::statement('UPDATE `orders` SET user_id=1');

        Schema::table('orders', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('orders', function($table) {
            $table->dropForeign('orders_user_id_foreign');
            $table->dropColumn('user_id');
            $table->dropColumn('name');
        });
    }

}
