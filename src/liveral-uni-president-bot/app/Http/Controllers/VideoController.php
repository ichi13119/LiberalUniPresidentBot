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

        $video = new Video();
        return view('videos/create',[
            'url' => route('videos.create'),
            'video' => $video
            ]);
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
        $video = Video::find($id);
        return view('videos/edit', [
            'url' => route('videos.update', ['id' => $id]),
            'video' => $video
            ]);
    }

    public function update(Request $request,int $id)
    {
        $video = Video::find($id);
        $video->title = $request->title;
        $video->url = $request->url;
        $video->subtitles = $request->subtitles;
        $video->save();
        return redirect()->route('videos.index');
    }


}
