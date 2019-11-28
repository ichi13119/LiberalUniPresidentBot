<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportantWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('important_words', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('video_id')->unsigned();
            $table->foreign('video_id')->references('id')->on('videos');
            $table->string('word', 50);
            $table->integer('frequency')->default(0);
            $table->double('tf', 8, 4)->default(0.0);
            $table->double('idf', 8, 4)->default(0.0);
            $table->timestamps();

            //外部制約
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('important_words');
    }
}
