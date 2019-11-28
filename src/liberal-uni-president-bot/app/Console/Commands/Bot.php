<?php

namespace App\Console\Commands;

use App;
use App\ChatWorkApi;
use App\Models\LiberalCommunityUser;
use Illuminate\Console\Command;

class Bot extends Command
{
    protected $signature = 'liberal:bot';
    protected $description = '自己紹介未完了 または プロフィール写真未設定 は BOTがリプライ';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $chatwork_api = new ChatWorkApi();

        // BOTくんリプライを行わないルームを指定する
        // 学長つぶやきチャット, 全体連絡チャット, 学長礼拝堂
        $skip_room_id = [162530947, 161569238, 161569222];

        foreach ((array)$chatwork_api->getMyRooms() as $room) {
            if (App::environment('production')) {
                // 本番環境ではリプライを行わないルームの場合スキップする
                if (in_array($room['room_id'], $skip_room_id, true)) continue;
            } else if (App::environment('local')) {
                // 開発環境ではテストを行う1つのルームにしかリプライしない(本番環境に対して誤動作を防ぐ対応）
                if ($room['room_id'] != config('app.test_room_id')) continue;
            }

            if($room['type'] != 'group') continue;

            $complete_bot_reply = [];
            foreach ((array)$chatwork_api->getMessages($room['room_id']) as $message) {
                $postMessageCase = [];

                // 自己紹介していない時 $is_liberal_community_user = false
                $is_liberal_community_user = LiberalCommunityUser::where('account_id', $message['account']['account_id'])->count() > 0;
                if (!$is_liberal_community_user) $postMessageCase = array_merge($postMessageCase, ['addSelfInfoMessage']);

                // プロフィール写真未設定の時 $is_default_avatar_image = true
                $avatar_image_url = $message['account']['avatar_image_url'];
                $pattern = '/https:\/\/appdata.chatwork.com\/avatar\/ico_default_.*/';
                $is_default_avatar_image = preg_match($pattern, $avatar_image_url) === 1;
                if ($is_default_avatar_image) $postMessageCase = array_merge($postMessageCase, ['addProfileMessage']);

                // 自己紹介していない　または　プロフィール写真未設定 アカウントの時 BOTくんがリプライする
                if ((!$is_liberal_community_user || $is_default_avatar_image) && !in_array($message['account']['account_id'], $complete_bot_reply, true)) {
                    $account_id = $message['account']['account_id'];
                    $room_id = $room['room_id'];
                    $message_id = $message['message_id'];
                    $post_message = $chatwork_api->formatMessage($room_id, $account_id, $message_id, $this->getPostMessage($postMessageCase));
                    $chatwork_api->postMessage($room_id, $post_message);
                    \Log::debug('SUCCESS BOT REPLY', [$room, $message]);
                    $complete_bot_reply[] = $message['account']['account_id'];
                } else {
                    \Log::debug('SUCCESS BOT', [$room, $message]);
                }
            }
        }
    }

    /**
     * 送信するメッセージ取得
     * @param array $case
     * @return string
     */
    private function getPostMessage($case)
    {
        $message = [];
        if (in_array('addProfileMessage', $case, true)) {
            $message = array_merge($message, [
                    <<<EOF
ガイドラインは必ず最後まで
目を通してな^ ^
最低限のルールや。
プロフ設定についても書いてるで〜
https://liberaluni.com/yuru-community-precautions#2
EOF
                ]
            );
        }

        if (in_array('addSelfInfoMessage', $case, true)) {
            $message = array_merge($message, [
                    <<<EOF
自己紹介チャット退室済みの人は
再度書き込みをお願いします〜
●自己紹介チャット
https://www.chatwork.com/g/larts-list
EOF
                ]
            );
        }

        return implode($message, "\n\n");
    }
}
