<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatelongtailinstsTable extends Migration
{

    public function up()
    {
      Schema::create('longtailinsts', function(Blueprint $table) {
          $table->increments('id')->unique();
          $table->integer('type');
          $table->string('url', 300)->nullable();
          $table->string('credentialsJSON',256);
          $table->string('productsArray',256)->nullable();
          $table->string('currencyCode',3);
          $table->boolean('has_mfa');
          $table->string('mfaArray',256)->nullable();
          $table->string('Name', 1024);
          $table->timestamps();
      });
    }

    public function down()
    {
        Schema::drop('longtailinsts');
    }
}
