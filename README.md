# リベラルアーツ大学学長BOT（非公式）
## 概要
学長BOTに対して質問すると、公開されている動画からおすすめを出してくれるBOTです。  
動画の字幕を形態素解析し特徴的な単語を抽出しています。

## 機能一覧
- おすすめ動画回答
- 動画字幕解析（バッチ処理）

## 画面
- QA画面(/question)
- 各動画抽出記録確認画面（管理者用）(/admin)

## テーブル
- 動画:Videos
- 字幕:Subtitles
- 重要単語:ImportantWords
- 自己紹介ホワイトリスト:Whitelists

## 開発環境構築手順
```
** git, dockerのインストールについてはすでに行なっているものとして、書いています。

// 開発環境のディレクトリ作成
mkdir 作業用ディレクトリ名

// ディレクトリ移動
cd 作業用ディレクトリ名

// ディレクトリ直下にボット環境をクローン（直下にクローンするために、後ろにコンマがついています。）
git clone https://github.com/IkumaHayashi/LiberalUniPresidentBot.git .

// ディレクトリ内を確認
ls -la

// dockerコンテナ作成
docker-compose up -d

// コンテナに接続
docker-compose exec app sh

// 現在いるディレクトリを確認(liveral-uni-president-botがあるはずです）
ls

// liveral-uni-president-botに移動
cd liveral-uni-president-bot

// phpのパッケージ管理ツールcomposerをインストール
composer install

// laravelのenvファイル（DBなどの設定が書かれています）を作成します。
cp .env.example .env

// おまじない（調べてみると色々知れるかも）
php artisan key:generate

.envのデータベースに関する設定を以下の内容に変更(エディターやvimなどで変更してください)
DB_USERNAME=root
DB_PASSWORD=root

また、YoutubeのURLを指定することでタイトルやサムネイルを表示するようにしています。
利用にあたり、YoutubeのAccessKeyが必要になりますので、下記ページを参考の上取得してください。
https://qiita.com/chieeeeno/items/ba0d2fb0a45db786746f

取得したら.envの下記項目に記載してください。
YOUTUBE_ACCESS_KEY=

// データベースを作成
php artisan migrate

// 動作確認
localhost:10080

// botくん 自己紹介していない または プロフィール写真未設定時の
// リプライ動作確認は次のリポジトリのREADME.mdに従って
// .env の API_TOKEN  WEBHOOK_TOKEN  TEST_ROOM_ID　を設定してください。
https://github.com/hitoshi-kakihana/chatwork_bot_laravel
```

## 本番環境
```
// ホストOSで crontab 設定　(botくんリプライチェックを毎分実行)
crontab -e
* * * * * docker-compose exec app /bin/sh -c "cd /work/liveral-uni-president-bot && php artisan schedule:run >> /dev/null 2>&1"

// .env を編集
APP_ENV=production
API_TOKEN="本番専用のtokenを記入"
WEBHOOK_TOKEN="本番専用のtokenを記入"
TEST_ROOM_ID=""

// 自己紹介プレッドシートの「ホワイトリスト」に登録されている、
// ユーザーIDを DB の whitelistsテーブルのaccount_idカラムに全てINSERTしてください 
// （本番反映初回だけ必要な作業）
https://docs.google.com/spreadsheets/d/1QBosbUraW5sgDeKtJWcusfd_J15T2Htz5KN5KbauoHk/edit#gid=1820809320
```