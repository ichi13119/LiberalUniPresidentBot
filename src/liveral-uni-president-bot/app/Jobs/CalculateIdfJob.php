<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Video;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class CalculateIdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Redis::throttle('key')->allow(1)->every(300)->then(function () {
            Log::debug('[CalculateIdf]idfの計算を開始します');
            $videos = Video::all();
            foreach ($videos as $video) {
                $video->calculateIdf();
            }
            Log::debug('[CalculateIdf]idfの計算を終了しました');
        }, function () {
            Log::debug('[CalculateIdf]ロックできませんでした。');
        });



    }
}
