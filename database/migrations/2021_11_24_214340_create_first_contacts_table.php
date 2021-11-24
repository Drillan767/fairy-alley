<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirstContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('first_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->date('birthday')->nullable();
            $table->string('email')->unique();
            $table->string('phone', 20);
            $table->string('pro', 20);
            $table->string('work')->nullable();
            $table->string('address')->nullable();
            $table->string('address2')->nullable();
            $table->string('zipcode', 5)->nullable();
            $table->string('city')->nullable();
            $table->string('family_situation');
            $table->integer('nb_children');
            $table->text('health_issues')->nullable();
            $table->text('current_health_issues')->nullable();
            $table->text('medical_treatment')->nullable();
            $table->text('sports')->nullable();
            $table->boolean('gym_history')->default(false);
            $table->text('objectives')->nullable();
            $table->text('other_data')->nullable();
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
        Schema::dropIfExists('first_contacts');
    }
}
