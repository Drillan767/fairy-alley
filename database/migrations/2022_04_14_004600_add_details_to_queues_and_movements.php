<?php

use App\Models\Lesson;
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
    public function up(): void
    {
        Schema::table('movements', function (Blueprint $table) {
            $table->boolean('by_admin')->default(false);
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->string('type', 60);
        });

        Lesson::query()->update(['gender' => '[]']);

        Schema::drop('queues');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
