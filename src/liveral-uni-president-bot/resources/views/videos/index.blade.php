
@extends('layouts.app')
@section('title', '動画登録画面')
@section('content')
    <div class="container">
        <div class="row">
            <nav class="panel panel-default">
                <div class="panel-heading">動画一覧</div>
                    <div class="panel-body">
                        <a href="{{ route('videos.create') }}" class="btn btn-default btn-block">
                            動画を追加する
                        </a>

                        <table class="table">
                        <thead>
                            <tr>
                            <th>タイトル</th>
                            <th>Link</th>
                            <th>字幕</th>
                            <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($videos as $video)
                            <tr>
                            <td>{{ $video->title }}</td>
                            <td>
                                <a href="{{ $video->url }}">Link</a>
                            </td>
                            <td><textarea>{{ $video->subtitles }}</textarea></td>
                            <td><a href="{{ route('videos.edit', ['id' => $video->id]) }}">編集</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </nav>
        </div>
    </div>
    @endsection
