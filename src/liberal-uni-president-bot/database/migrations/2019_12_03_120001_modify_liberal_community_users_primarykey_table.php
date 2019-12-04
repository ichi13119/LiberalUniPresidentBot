<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyLiberalCommunityUsersPrimarykeyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liberal_community_users', function (Blueprint $table): void {
            $table->dropColumn('id');
            $table->dropUnique('liberal_community_users_account_id_unique');
        });
        Schema::table('liberal_community_users', function (Blueprint $table): void {
            $table->primary('account_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('liberal_community_users', function (Blueprint $table): void {
            $table->dropPrimary('PRIMARY');
        });
        Schema::table('liberal_community_users', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->unique('account_id');
        });
    }
}
