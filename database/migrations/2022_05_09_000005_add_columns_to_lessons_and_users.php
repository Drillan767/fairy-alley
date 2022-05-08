<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('resubscription_status')->nullable();
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->string('room', 30)->nullable();
            $table->string('color', 7);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('resubscription_status');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('room');
            $table->dropColumn('color');
        });
    }
};
