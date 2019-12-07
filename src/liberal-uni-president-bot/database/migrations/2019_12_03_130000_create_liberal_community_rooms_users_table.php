<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiberalCommunityRoomsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liberal_community_rooms_users', function (Blueprint $table) {
            $table->unsignedBigInteger('room_id')->comment('chatworkから取得できるルームID');
            $table->unsignedBigInteger('account_id')->comment('chatworkから取得できるアカウントID');
            $table->date('enter_date')->comment('ユーザーが部屋に入室した日');
            $table->date('leave_date')->nullable()->comment('ユーザーが部屋から退室した日');
            $table->timestamps();

            $table->primary(['room_id', 'account_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('liberal_community_rooms_users');
    }
}
