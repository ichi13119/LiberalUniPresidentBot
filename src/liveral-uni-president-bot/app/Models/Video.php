<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    //
    public function importantWords()
    {
        return $this->hasMany('App\Models\ImportantWord');
    }


    public function setImportantWords()
    {

        //現在挿入されているレコードを削除
        $this->importantWords()->delete();

        //重要度を算出
        $words = $this->generateImportantWords();

        //DBに保存
        foreach ($words as $key => $ranking) {
            $importantWord = new \App\Models\ImportantWord();
            $importantWord->word = $key;
            $importantWord->ranking = $ranking;
            $this->importantWords()->save($importantWord);
        }

    }

    /**
     *  字幕から重要単語を算出する
     *
     * @return void
     */
    public function generateImportantWords(){

        //文字列を解析
        $this->subtitles = preg_replace('/(\d{2}:\d{2})(\n|\r\n|\r)/', '',$this->subtitles);
        $mecab = new \Mecab\Tagger();
        $nodes = $mecab->parseToNode($this->subtitles);

        //形態素ごとに名詞かどうか、重要度はいくつかを算出
        $words = array();
        $compoundNoun = '';

        foreach ($nodes as $n) {

            $result = explode(',', $n->getFeature());

            //空白は無視
            if($n->getSurface() == '')
                continue;

            //名詞ではない かつ 前も名詞ではない場合はスキップ
            if($compoundNoun == '' && $result[0] != '名詞'){
                $compoundNoun = '';
                continue;

            //名詞ではない かつ 複合名詞が空でない場合は、単語の区切りとしてカウント
            }else if($compoundNoun != '' && $result[0] != '名詞'){

                //ひらがな１文字は除外する
                if(preg_match('/^[ぁ-ん]$/u', $compoundNoun)){
                    $compoundNoun = '';
                    continue;
                }

                //単語を格納
                if(empty($words[$compoundNoun])){
                    $words[$compoundNoun] = 1;
                }else{
                    $words[$compoundNoun]++;
                }

                $compoundNoun = '';
                continue;

            //名詞 かつ 複合名詞の場合は、複合名詞として結合する
            }else if($compoundNoun != '' && $result[0] == '名詞'){
                $compoundNoun .= $n->getSurface();

            //名詞の場合、複合名詞として一旦格納
            }else{
                $compoundNoun .= $n->getSurface();
            }

        }

        arsort($words);

        return $words;
    }
}
