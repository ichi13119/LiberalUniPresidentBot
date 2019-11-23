<?php

namespace App;

class ChatWorkApi
{
    public $client = null;
    public $headers = null;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client(['base_uri' => 'https://api.chatwork.com']);
        $this->headers = ['X-ChatWorkToken' => config('app.api_token')];
    }

    /**
     * 自分のチャット一覧の取得 http://developer.chatwork.com/ja/endpoint_rooms.html#GET-rooms
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getMyRooms()
    {
        $response = $this->client->request('GET', '/v2/rooms', ['headers' => $this->headers,]);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * チャットのメンバー一覧を取得 http://developer.chatwork.com/ja/endpoint_rooms.html#GET-rooms-room_id-members
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRoomMembers($room_id)
    {
        $response = $this->client->request('GET', "/v2/rooms/$room_id/members", ['headers' => $this->headers]);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * チャットのメッセージ一覧を取得。パラメータ未指定だと前回取得分からの差分のみを返します。(最大100件まで取得) http://developer.chatwork.com/ja/endpoint_rooms.html#GET-rooms-room_id-messages
     * @param $room_id
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getMessages($room_id, $force = 0)
    {
        $response = $this->client->request('GET', "/v2/rooms/$room_id/messages?force=$force", ['headers' => $this->headers]);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * メッセージ情報を取得 http://developer.chatwork.com/ja/endpoint_rooms.html#GET-rooms-room_id-messages-message_id
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getMessage($room_id, $message_id)
    {
        $response = $this->client->request('GET', "/v2/rooms/$room_id/messages/$message_id", ['headers' => $this->headers]);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * チャットに新しいメッセージを追加 http://developer.chatwork.com/ja/endpoint_rooms.html#POST-rooms-room_id-messages
     * @param $room_id
     * @param $message
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function postMessage($room_id, $message)
    {
        return $this->client->request('POST', "/v2/rooms/$room_id/messages",
            [
                'headers' => $this->headers,
                'form_params' => [
                    'body' => $message,
                ]
            ]);
    }

    /**
     * 送信するメッセージを整形
     * @param $room_id
     * @param $account_id
     * @param $message_id
     * @param $message
     * @param $title
     * @return string
     */
    public function formatMessage($room_id, $account_id, $message_id, $message, $title = 'プロフ設定と自己紹介が終わってないと発言禁止〜')
    {
        // ChatWorkのメッセージ記法を利用してチャットに表示される文言を装飾します
        // メッセージ記法 : http://developer.chatwork.com/ja/messagenotation.html
        return "[rp aid=$account_id to=$room_id-$message_id][piconname:$account_id]さん[info][title]" . $title . "[/title]" . $message . "[/info]";
    }

    /**
     * 送信するメッセージ取得
     * @param array $case
     * @return string
     */
    public function getPostMessage($case)
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
