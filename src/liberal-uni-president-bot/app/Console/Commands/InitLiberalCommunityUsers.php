<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ChatWorkApi;
use App\Models\LiberalCommunityRoom;
use App\Models\LiberalCommunityRoomUser;
use App\Models\LiberalCommunityUser;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class InitLiberalCommunityUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'liberal:initLiberalCommunityUsers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'リベ大の全ユーザーを取得し新規作成する';

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
        $start = time();
        $now = Carbon::now();

        $chatwork_api = new ChatWorkApi();

        $user_list = [];
        foreach (LiberalCommunityRoom::all() as $room) {
            $room_users = [];
            $other = json_decode($room['other'], true);
            if ($other['type'] === 'group') {
                if ($other['role'] === 'admin') {
                    $users = $chatwork_api->getRoomMembers($room->room_id);
                    foreach ($users as $user) {
                        $user_list[$user['account_id']] = [
                            'account_id' => $user['account_id'],
                            'other' => json_encode($user, true),
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];

                        $room_users[] = [
                            'account_id' => $user['account_id'],
                            'room_id' => $room->room_id,
                            'enter_date' => $now,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                    \DB::table('liberal_community_rooms_users')->insert($room_users);
                }
            }
        }

        // 1000ユーザーずつバルクinsert
        foreach (\array_chunk($user_list, 1000) as $users) {
            \DB::table('liberal_community_users')->insert($users);
        }

        echo 'time: ' . (time() - $start) . "[s]\n";
    }
}
