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
            $table->foreignId('request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('break_id')->constrained()->cascadeOnDelete();

            // 修正前
            $table->time('old_break_start')->nullable();
            $table->time('old_break_end')->nullable();

            // 修正後
            $table->time('new_break_start')->nullable();
            $table->time('new_break_end')->nullable();

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
