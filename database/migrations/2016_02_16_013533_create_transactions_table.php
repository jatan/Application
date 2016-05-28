<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(/**
         * @param Blueprint $table
         */
            'transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank_accounts_id');
            $table->float('amount');
            $table->string('date');
            $table->string('name');
            $table->string('location_city');
            $table->string('location_state');
            $table->boolean('pending');
            $table->string('type_primary');
            $table->string('category');
            $table->integer('category_id');
            $table->string('score');
            $table->text('plaid_core');
            $table->timestamps();

            $table->foreign('bank_accounts_id')
                ->references('id')
                ->on('bank_accounts')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transactions');
    }
}
