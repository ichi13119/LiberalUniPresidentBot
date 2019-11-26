<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LiberalCommunityUser;

class SelfIntroController extends Controller
{
    // 自己紹介ホワイトリスト追加
    public function webhook(Request $request)
    {
        if ($this->validationSignature($request) === false) return false;
        $is_liberal_community_user = LiberalCommunityUser::where('account_id', $request->input('webhook_event.account_id'))->count() > 0;
        if (!$is_liberal_community_user) {
            $liberal_community_user = new LiberalCommunityUser;
            $liberal_community_user->account_id = $request->input('webhook_event.account_id');
            $liberal_community_user->other = json_encode($request->all(), true);
            $liberal_community_user->save();
            \Log::debug('SUCCESS WEBHOOK ADD LiberalCommunityUser', [$request->all()]);
        } else {
            \Log::debug('SUCCESS WEBHOOK', [$request->all()]);
        }
    }

    private function validationSignature(Request $request)
    {
        $secret_key = base64_decode(config('app.webhook_token'));
        $request_body = $request->getContent();
        $digest = hash_hmac('sha256', $request_body, $secret_key, true);
        $signature = base64_encode($digest);
        return ($signature === $request->header('X-ChatWorkWebhookSignature') ? true : false);
    }
}
