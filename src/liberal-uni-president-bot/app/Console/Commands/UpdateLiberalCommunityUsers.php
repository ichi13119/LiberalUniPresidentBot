<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ChatWorkApi;
use App\Models\LiberalCommunityRoom;
use App\Models\LiberalCommunityRoomUser;
use App\Models\LiberalCommunityUser;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class UpdateLiberalCommunityUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'liberal:updateLiberalCommunityUsers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'リベ大の全ユーザーを取得し更新する';

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

        $database_all_user_ids = Arr::pluck(LiberalCommunityUser::get(['account_id'])->toArray(), 'account_id');
        $chatwork_all_user_ids = [];
        foreach (LiberalCommunityRoom::all() as $room) {
            $other = json_decode($room['other'], true);
            if ($other['type'] === 'group') {
                if ($other['role'] === 'admin') {
                    $chatwork_room_users = $chatwork_api->getRoomMembers($room->room_id);
                    $database_room_user_ids = Arr::pluck(LiberalCommunityRoomUser::where('room_id', $room->room_id)->get(['account_id'])->toArray(), 'account_id');
                    $chatwork_room_user_ids = [];
                    foreach ($chatwork_room_users as $chatwork_room_user) {
                        // データベースにいないユーザーは新規登録。今回すでに登録したユーザーはスルー。
                        if (!\in_array($chatwork_room_user['account_id'], $database_all_user_ids) && !\in_array($chatwork_room_user['account_id'], $chatwork_all_user_ids)) {
                            echo 'user: ' . $chatwork_room_user['account_id'] . " 新規登録\n";
                            LiberalCommunityUser::create([
                                'account_id' => $chatwork_room_user['account_id'],
                                'other' => json_encode($chatwork_room_user, true),
                            ]);
                        }

                        // データベース上で部屋に所属していないユーザーは新規登録
                        if (!\in_array($chatwork_room_user['account_id'], $database_room_user_ids)) {
                            echo 'room: ' . $room->room_id . ', user: ' . $chatwork_room_user['account_id'] . " 入室\n";
                            LiberalCommunityRoomUser::create([
                                'account_id' => $chatwork_room_user['account_id'],
                                'room_id' => $room->room_id,
                                'enter_date' => Carbon::now(),
                            ]);
                        }

                        $chatwork_room_user_ids[] = $chatwork_room_user['account_id'];
                        $chatwork_all_user_ids[] = $chatwork_room_user['account_id'];
                    }

                    // 重複を取り除く。
                    $chatwork_all_user_ids = array_unique($chatwork_all_user_ids);

                    // データベース上で部屋に所属していたが、chatwork上では所属していないユーザーは退出とする。
                    // 1日の間に退出にするユーザーはそれほど多くないと想像してバルク処理ではなく１件ずつ処理する。
                    $room_users = LiberalCommunityRoomUser::where('room_id', $room->room_id)->whereNotIn('account_id', $chatwork_room_user_ids)->get();
                    foreach ($room_users as $user) {
                        echo 'room: ' . $room->room_id . ', user: ' . $user->account_id . " 退室\n";
                        $user->leave_date = Carbon::now();
                        $user->save();
                    }
                }
            }
        }

        // 1日の間に卒業するユーザーはそれほど多くないと想像してバルク処理ではなく１件ずつ処理する。
        $delete_users = LiberalCommunityUser::whereNotIn('account_id', $chatwork_all_user_ids)->get();
        foreach ($delete_users as $user) {
            echo $user->account_id . ": 削除\n";
            $user->delete();
        }

        echo 'time: ' . (time() - $start) . "[s]\n";
    }
}
