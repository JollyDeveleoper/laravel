<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('day')->default(1)->comment('День недели 1-7');
            $table->string('name', 255)->comment('Название пары');
            // Тут именно string, потому что иногда пары может быть 2 и надо поставить /
            $table->string('cabinet', 255)->comment('Номер кабинета');
            $table->string('teacher', 255)->comment('Имя преподавателя');
            $table->string('start_time', 255)->comment('Время начала пары');
            $table->string('end_time', 255)->comment('Время конца пары');
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
        Schema::dropIfExists('schedule');
    }
}
