<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('homepage')->default(false);
        });

        foreach (['selected_time', 'fallback_time'] as $column) {
            if (Schema::hasColumn('subscription', $column)) {
                Schema::table('subscription', function (Blueprint $table) {
                    $table->dropColumn($table);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
