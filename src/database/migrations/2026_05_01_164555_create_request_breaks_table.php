<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestBreaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests_breaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('break_id')->constrained('break_times')->cascadeOnDelete();

            // 修正後
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();

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
        Schema::dropIfExists('requests_breaks');
    }
}
