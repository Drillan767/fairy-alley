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
            $table->foreignId('media_id')->nullable()->constrained('medias');
            $table->text('health_data')->nullable();

            // seen / editable by admin
            $table->enum('account_type', ['check', 'cash'])->nullable();
            $table->date('deposit_paid_at')->nullable();
            $table->text('observations')->nullable();
            $table->boolean('pre_registration_signature')->default(false);
            $table->boolean('deposit_paid')->default(false);
            $table->dateTime('payment_received_at')->nullable();

            // automated
            $table->string('last_year_class')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->string('possibility_1')->nullable();
            $table->string('possibility_2')->nullable();
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
