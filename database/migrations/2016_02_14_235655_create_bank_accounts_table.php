<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            //$table->increments('id');
            $table->string('id')->unsinged();
            $table->integer('user_id');
            $table->string('bank_name');
            $table->string('account_type');
            $table->string('account_subtype');
            $table->string('current_balance');
            $table->string('available_balance');
            $table->string('limit');
            $table->string('name');
            $table->string('number');
            $table->string('access_token');
            $table->timestamps();
            $table->text('plaid_core');

            $table->primary('id');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bank_accounts');
    }
}
