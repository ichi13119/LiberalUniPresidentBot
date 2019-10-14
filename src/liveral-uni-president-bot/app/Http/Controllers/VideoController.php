<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::all();

        return view('videos/index', [
            'videos' => $videos,
        ]);
        //$subtitles = \SubtitlesExtractingService::extractSubtitlesFromTranscripTtext('https://www.youtube.com/watch?v=fg7ZMuRp5D4');
        //return $subtitles;
    }

    public function showCreateForm()
    {
        return view('videos/create');
    }

    public function create(Request $request)
    {
        $video = new Video();
        $video->title = $request->title;
        $video->url = $request->url;
        $video->subtitles = $request->subtitles;
        $video->save();
        return redirect()->route('videos.index');
    }

    public function showEditForm(int $id)
    {
        return view('videos/create');
    }


}
