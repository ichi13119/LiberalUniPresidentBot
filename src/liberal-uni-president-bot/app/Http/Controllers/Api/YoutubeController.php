<?php

namespace App\Http\Controllers\Api;

use App\Config;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class YoutubeController extends Controller
{
    public function __construct()
    {
        $this->middleware('UnescapeJsonResponse');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getVideoInformation(Request $request)
    {
        $videoId = $request->videoId;
        $accessKey = config('app.youtube_access_key');
        $url = 'https://www.googleapis.com/youtube/v3/videos?id='.$videoId;
        $url .= '&key='.$accessKey.'&fields=items(id,snippet(channelTitle,title,description,thumbnails),statistics)&part=snippet,contentDetails,statistics';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);
        curl_close($curl);

        return response()->json(
            json_decode($result)->items[0]
            , 200
            , ['Content-Type' => 'application/json']
            , JSON_UNESCAPED_SLASHES);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
