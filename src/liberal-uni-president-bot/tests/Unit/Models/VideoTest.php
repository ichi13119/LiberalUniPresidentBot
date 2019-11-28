<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Video;
use ReflectionClass;
use Log;

class VideoTest extends TestCase
{
    /**
     * @test
     */
    public function 字幕文字列から時刻と改行コードを削除する()
    {

        //前準備
        $video = new Video();
        $video->subtitles = "00:00
こんにちは量ですそれではお金の勉強の続きやっていきたいとおもいます
00:05
今日はこのお金のなる木猫のお金のなる木を増やしていたかなね人生にするっていうの";
        $expect = "こんにちは量ですそれではお金の勉強の続きやっていきたいとおもいます
今日はこのお金のなる木猫のお金のなる木を増やしていたかなね人生にするっていうの";


        //実行
        //検証
        $this->assertEquals($expect, $video->subtitles);

    }

    /**
     *  @test
     */
    public function 字幕文字列から単名詞と複合名詞の出現頻度とtfを抽出する()
    {
        //前準備
        $video = new Video();
        $video->subtitles = '学長の保険不要論。保険は不要です。';

        $reflection = new ReflectionClass($video);
        $method = $reflection->getMethod('generateFrequencyAndTfs');
        $method->setAccessible(true);
        $expect =['学長' => [
                    'frequency' => 1
                    , 'tf' => 1 / 11
                    ]
                , '保険' => [
                    'frequency' => 2
                    , 'tf' => 2 / 11
                    ]
                , '不要' => [
                    'frequency' => 2
                    , 'tf' => 2 / 11
                    ]
                , '論' => [
                    'frequency' => 1
                    , 'tf' => 1 / 11
                    ]
                , '保険不要論' => [
                    'frequency' => 1
                    , 'tf' => 1 / 11
                    ]
                ];

        //実行
        $actual = $method->invoke($video);
        asort($actual);

        //検証
        $this->assertEquals($expect, $actual);

    }

}
