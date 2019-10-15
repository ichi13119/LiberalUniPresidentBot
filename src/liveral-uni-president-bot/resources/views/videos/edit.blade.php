
@extends('layouts.app')
@section('title', '動画登録画面')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col col-md-offset-3 col-md-6">
                <nav class="panel panel-default">
                    <div class="panel-heading">動画を編集する</div>
                    <div class="panel-body">
                        @component('videos.form', [
                                    'url' => $url,
                                    'video'=> $video])
                        @endcomponent
                    </div>
          </nav>
        </div>
      </div>
    </div>
    @endsection
