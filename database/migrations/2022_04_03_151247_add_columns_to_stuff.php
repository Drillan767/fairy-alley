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
        Schema::table('services', function (Blueprint $table) {
            $table->enum('type', ['s', 'p'])->default('s');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->enum('gender', ['H', 'F']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('gender');
        });
    }
};
