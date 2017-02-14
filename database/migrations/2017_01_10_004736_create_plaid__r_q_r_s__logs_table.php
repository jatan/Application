<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaidRQRSLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plaid__r_q_r_s__logs', function (Blueprint $table) {
            $table->increments('LogID');
            $table->string('URL', 1024)->nullable();
            $table->text('Request')->nullable();
            $table->text('Response')->nullable();
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
        Schema::drop('plaid__r_q_r_s__logs');
    }
}
