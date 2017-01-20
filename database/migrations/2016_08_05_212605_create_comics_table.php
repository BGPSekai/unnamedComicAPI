<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('summary');
            $table->string('author');
            $table->string('type');
            $table->integer('published_by')->unsigned();
            $table->integer('chapters')->unsigned()->default(0);
            $table->integer('favorites')->unsigned()->default(0);
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
        Schema::drop('comics');
    }
}
