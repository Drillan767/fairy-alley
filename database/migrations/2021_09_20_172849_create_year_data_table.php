<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYearDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('year_data', function (Blueprint $table) {
            $table->id();

            // filled by user
            $table->text('health_data')->nullable();

            // seen / editable by admin
            $table->enum('account_type', ['check', 'cash', 'transfer'])->nullable();
            $table->date('deposit_paid_at')->nullable();
            $table->text('observations')->nullable();
            $table->date('pre_registration_signature')->nullable();

            // automated
            $table->string('last_year_class')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->string('reply_transmitted_via')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('year_data');
    }
}
