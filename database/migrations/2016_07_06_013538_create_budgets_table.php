<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgets', function(Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('User_ID');
            $table->string('Name', 200);
            $table->integer('SetValue')->nullable();
            $table->integer('SpentValue')->nullable();
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
        Schema::drop('budgets');
    }
}
