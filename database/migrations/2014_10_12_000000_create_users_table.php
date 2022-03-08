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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->date('birthday')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('gender', 1)->nullable();
            $table->string('phone')->nullable();
            $table->string('pro')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('zipcode', 5)->nullable();
            $table->string('city')->nullable();
            $table->text('other_data')->nullable();
            $table->string('family_situation')->nullable();
            $table->integer('nb_children')->default(0);
            $table->text('health_issues')->nullable();
            $table->text('current_health_issues')->nullable();
            $table->text('medical_treatment')->nullable();
            $table->text('sports')->nullable();
            $table->boolean('gym_history')->default(false);
            $table->text('objectives')->nullable();
            $table->foreignId('lesson_id')->nullable()->constrained();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
