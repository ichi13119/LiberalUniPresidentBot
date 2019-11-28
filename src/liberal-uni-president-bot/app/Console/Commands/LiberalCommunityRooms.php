<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ChatWorkApi;
use App\Models\LiberalCommunityRoom;

class LiberalCommunityRooms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'liberal:updateLiberalCommunityRooms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'BOTが所属するルームを取得';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $chatwork_api = new ChatWorkApi();

        //参加しているすべての部屋を取得し、テーブルになければ追加する
        $rooms = (array)$chatwork_api->getMyRooms();
        foreach ($rooms as $room) {

            $isExsits = LiberalCommunityRoom::withTrashed()->where('room_id', $room['room_id'])->exists();
            $softDeletedRoom = LiberalCommunityRoom::onlyTrashed()->where('room_id', $room['room_id'])->first();

            if(!$isExsits){
                $liberalCommunityRoom = new LiberalCommunityRoom;
                $liberalCommunityRoom->room_id = $room['room_id'];
                $liberalCommunityRoom->other = json_encode($room, true);
                $liberalCommunityRoom->save();
                \Log::debug('SUCCESS ADD LiberalCommunityRoom', [$room]);
            }else if(isset($softDeletedRoom)){
                $softDeletedRoom->restore();
                \Log::debug('SUCCESS RESTORE LiberalCommunityRoom', [$room]);
            }else{
                \Log::debug('ALREADY EXISTS LiberalCommunityRoom', [$room]);
            }

        }

        //テーブルの値をすべて取得し、参加している部屋がなければテーブルから論理削除する
        foreach (LiberalCommunityRoom::get() as $liberalCommunityRoom){
            $isExsits = false;
            foreach ($rooms as $room) {
                $key = array_search($liberalCommunityRoom->room_id, $room);
                if($key === 'room_id'){
                    $isExsits = true;
                    break;
                }
            }

            if(!$isExsits){
                $liberalCommunityRoom->delete();
            }
        }

    }
}
