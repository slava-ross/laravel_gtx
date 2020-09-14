<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_city');
            $table->foreignId('id_author');
            $table->string('title');
            $table->text('comment_text');
            $table->unsignedSmallInteger('rating');
            $table->string('img')->nullable();
            $table->timestamps();
            $table->foreign('id_city')->references('id')->on('cities');
            $table->foreign('id_author')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
