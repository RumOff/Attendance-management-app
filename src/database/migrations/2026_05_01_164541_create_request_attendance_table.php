<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained()->cascadeOnDelete();

            // 修正前の値
            $table->time('old_clock_in')->nullable();
            $table->time('old_clock_out')->nullable();

            // 修正後の値
            $table->time('new_clock_in')->nullable();
            $table->time('new_clock_out')->nullable();
            
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
        Schema::dropIfExists('requests_attendance');
    }
}
