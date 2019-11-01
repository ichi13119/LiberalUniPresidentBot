
@extends('layouts.app')
@section('title', '動画登録画面')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col col-md-offset-3 col-md-6">
                <nav class="panel panel-default">
                    <div class="panel-heading">動画詳細</div>
                    <div class="panel-body">

                        <div class="form-group">
                            <label for="title">タイトル</label>
                            {{ $video->title }}
                        </div>
                        <div class="form-group">
                            <label for="url">URL</label>
                            {{ $video->url }}
                        <div class="form-group">
                            <label for="subtitles">字幕</label>
                            <textarea class="form-control" name="subtitles" id="subtitles" rows="10" cols="40" readonly">{{ $video->subtitles }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="important_words">重要単語</label>
                            <table>
                                <tr>
                                    <th>単語</th>
                                    <th>重要度</th>
                                </tr>
                                @forelse ($video->importantWords as $importantWord)
                                <tr>
                                    <td>{{$importantWord->word}}</td>
                                    <td>{{$importantWord->ranking}}</td>
                                </tr>
                                @empty
                                    解析が終わってません
                                @endforelse
                        </div>

                    </div>
          </nav>
        </div>
      </div>
    </div>
@endsection
