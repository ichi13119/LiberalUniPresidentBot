<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTfidfToImportantWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('important_words', function (Blueprint $table) {
            $table->double('tfidf', 8, 4)->virtualAs('tf * ( idf + 1 )');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('important_words', function (Blueprint $table) {
            $table->dropColumn('tfidf');
        });
    }
}
