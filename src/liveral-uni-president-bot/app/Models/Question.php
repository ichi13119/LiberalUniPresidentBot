<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class Question extends Model
{

    const UPDATED_AT = null;

    public function __construct($question)
    {
        $this->question = $question;
        $this->answer = $this->setAnswer();
    }

    public function setAnswer()
    {

        //文字列を解析
        $mecab = new \Mecab\Tagger();
        $nodes = $mecab->parseToNode($this->question);

        //形態素解析して名詞に対して合致する動画を取得する
        $recomendVideos = [];
        $questionWords = [];
        foreach ($nodes as $node) {

            $result = explode(',', $node->getFeature());
            if($result[0] != '名詞')
                continue;

            $word = $node->getSurface();

            $importantWords = ImportantWord::where('word', $word)
                                ->orderByDesc('tfidf')
                                ->take(5)->get();

            if(count($importantWords) == 0){
                continue;
            }else{
                $questionWords[] = $word;
            }

            foreach ($importantWords as $importantWord) {
                $video = $importantWord->video;
                if(!in_array($video, $recomendVideos, true)){
                    $recomendVideos[] = $video;
                }
            }
        }

        //回答の文字列を生成する
        $answer = '';
        if(count($questionWords) == 0){
            $answer = 'ごめんな、ちょうどいい動画見つからんかったわ！';
        }else{
            $answer = '君は'.implode('と', $questionWords).'について知りたいんやな。'.PHP_EOL;
            $answer .= 'そんな君におすすめの動画はこれや！'.PHP_EOL;
            foreach ($recomendVideos as $video) {
                $answer .= $video->title.PHP_EOL;
                $answer .= $video->url.PHP_EOL;
            }
            $answer .= '知識マッチョ目指して頑張ろな^^'.PHP_EOL;
        }

        return $answer;
    }

}
