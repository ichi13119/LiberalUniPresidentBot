<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class Video extends Model
{
    //
    public function importantWords()
    {
        return $this->hasMany('App\Models\ImportantWord');
    }

    /**
     * subtitle getter
     * 時刻文字列と改行を削除して返す
     *
     * @param [type] $value
     * @return string
     */
    public function getSubtitlesAttribute($value) : string
    {
        return preg_replace('/(\d{2}:\d{2})(\n|\r\n|\r)/', '', $value);
    }

    public function setImportantWords()
    {

        Log::debug('setImportantWords');
        //現在挿入されているレコードを削除
        $this->importantWords()->delete();

        //名詞の抽出と重要度の算出
        $frequencyAndTfs =  $this->generateFrequencyAndTfs();
        Log::debug($frequencyAndTfs);

        //DBに保存
        foreach ($frequencyAndTfs as $key => $frequencyAndTf) {
            $importantWord = new \App\Models\ImportantWord();
            $importantWord->word = $key;
            $importantWord->tf = $frequencyAndTf['tf'];
            $importantWord->frequency = $frequencyAndTf['frequency'];
            $this->importantWords()->save($importantWord);
        }

    }

    public function calculateIdf()
    {
        Log::debug('calculateIdf');
        $videos = Video::all();
        $importantWords = $this->importantWords()->get();

        foreach ($importantWords as $importantWord) {
            Log::debug($importantWord->word);
            $hasVideoCount = 0;

            foreach ($videos as $video) {
                if($video->hasImportantWord($importantWord->word))
                    $hasVideoCount++;
            }

            $idf = $hasVideoCount > 0 ? log10(count($videos) / $hasVideoCount) : log10(0);
            $importantWord->idf = $idf;
            $importantWord->save();
            Log::debug('idf = '.count($videos)." / $hasVideoCount");

        }
    }

    public function hasImportantWord($word) : bool
    {
        foreach ($this->importantWords()->get() as $importantWord) {
            if($importantWord->word == $word)
                return true;
        }
        return false;
    }


    /**
     * 字幕から単名詞、複合名詞を抽出する
     *
     * @return array 抽出した単名詞、複合名詞
     */
    private function generateFrequencyAndTfs() : array
    {

        //文字列を解析
        $mecab = new \Mecab\Tagger();
        $nodes = $mecab->parseToNode($this->subtitles);

        //形態素ごとに名詞かどうか、重要度はいくつかを算出
        $allWords = array();
        $words = array();
        $compoundNoun = '';

        foreach ($nodes as $n) {

            $result = explode(',', $n->getFeature());

            //空白は無視
            if($n->getSurface() == '')
                continue;

            //全単語の頻出回数を記録
            $this->incrementFrequency($allWords, $n->getSurface());

            //名詞ではない かつ 前も名詞ではない場合はスキップ
            if($compoundNoun == '' && $result[0] != '名詞'){
                continue;

            //名詞ではない かつ 複合名詞が空でない場合は、複合名詞としてカウント
            }else if($compoundNoun != '' && $result[0] != '名詞'){

                //ひらがな１文字は除外する
                if(preg_match('/^[ぁ-ん]$/u', $compoundNoun)){
                    $compoundNoun = '';
                    continue;
                }

                //複合名詞がまだ単名詞の場合は除外する
                if($compoundNoun == $n->getSurface()){
                    $compoundNoun = '';
                    continue;
                }

                //複合名詞を格納
                $this->incrementFrequency($words, $compoundNoun);
                $compoundNoun = '';

            //名詞 かつ 前の形態素も名詞の場合
            }else if($compoundNoun != '' && $result[0] == '名詞'){

                //前の名詞と複合名詞が一致する場合、前の名詞を単名詞としてカウント
                if($compoundNoun == $n->getPrev()->getSurface()){
                    $this->incrementFrequency($words, $n->getPrev()->getSurface());
                }

                $this->incrementFrequency($words, $n->getSurface());
                $compoundNoun .= $n->getSurface();

            //名詞 かつ 最初の出現の場合
            }else{
                $compoundNoun .= $n->getSurface();
            }
        }

        $frequencyAndTfs = array();
        $sumFrequency = array_sum($allWords);
        foreach ($words as $word => $value) {
            $frequencyAndTfs[$word] = ['frequency'=> $value
                                       , 'tf' => $value / $sumFrequency];
        }

        return $frequencyAndTfs;
    }

    private function incrementFrequency(&$words, $word)
    {
        isset($words[$word]) ? $words[$word]++ : $words[$word] = 1;
    }
}
