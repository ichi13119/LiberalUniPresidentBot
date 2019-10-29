<?php

namespace App\Observers;

use App\Models\Video;

class VideoObserver
{
    /**
     * Handle the video "created" event.
     *
     * @param  \App\Models\Video  $video
     * @return void
     */
    public function created(Video $video)
    {
    }

    /**
     * Handle the video "saved" event.
     *
     * @param  \App\Models\Video  $video
     * @return void
     */
    public function saved(Video $video)
    {
        $video->subtitles = preg_replace('/(\d{2}:\d{2})(\n|\r\n|\r)/', '',$video->subtitles);
        $mecab = new \Mecab\Tagger();
        $nodes = $mecab->parseToNode($video->subtitles);
        $words = array();
        $isCompoundNoun = false;
        $compoundNoun = '';
        foreach ($nodes as $n) {

            $result = explode(',', $n->getFeature());

            if($n->getSurface() == '')
                continue;

            if($isCompoundNoun == false && $result[0] != '名詞'){
                $compoundNoun = '';
                continue;
            }else if($isCompoundNoun && $result[0] != '名詞'){
                if(empty($words[$compoundNoun])){
                    $words[$compoundNoun] = 0;
                }
                $words[$compoundNoun]++;
                $compoundNoun = '';
                $isCompoundNoun = false;
                continue;
            }else if($isCompoundNoun && $result[0] == '名詞'){
                $compoundNoun .= $n->getSurface();
            }else{
                $compoundNoun .= $n->getSurface();
                $isCompoundNoun = true;
            }

            if(empty($words[$n->getSurface()])){
                $words[$n->getSurface()] = 0;
            }
            $words[$n->getSurface()]++;
        }
        //$words = array_filter($words, 'isThresholdValueOrMore');
        arsort($words);

        foreach ($words as $key => $ranking) {
            $importantWord = new \App\Models\ImportantWord();
            $importantWord->word = $key;
            $importantWord->ranking = $ranking;
            $video->importantWords()->save($importantWord);
        }

        \Log::debug(var_dump($words) . PHP_EOL);


    }

    /**
     * Handle the video "deleted" event.
     *
     * @param  \App\Models\Video  $video
     * @return void
     */
    public function deleted(Video $video)
    {
        //
    }

    /**
     * Handle the video "restored" event.
     *
     * @param  \App\Models\Video  $video
     * @return void
     */
    public function restored(Video $video)
    {
        //
    }

    /**
     * Handle the video "force deleted" event.
     *
     * @param  \App\Models\Video  $video
     * @return void
     */
    public function forceDeleted(Video $video)
    {
        //
    }
}
