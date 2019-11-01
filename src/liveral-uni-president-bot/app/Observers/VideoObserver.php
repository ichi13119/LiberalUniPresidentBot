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
        $video->setImportantWords();
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
