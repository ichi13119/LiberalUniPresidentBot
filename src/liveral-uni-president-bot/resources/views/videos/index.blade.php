
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
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($videos as $video)
                            <tr>
                                <td><a href="{{ route('videos.show', $video) }}">{{ $video->title }}</a></td>
                                <td>
                                    <a href="{{ $video->url }}"><i class="fab fa-youtube fa-2x"></i></a>
                                </td>
                                <td><textarea>{{ $video->subtitles }}</textarea></td>
                                <td>
                                    <button type="button" class="btn btn-info"
                                        onclick="location.href='{{ route('videos.edit', $video) }}'"
                                        value="編集">
                                        <i class="fas fa-edit fa-small"></i>
                                    </button>
                                </td>
                                <td>
                                    <form action="{{ route('videos.destroy', $video) }}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash fa-small"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </nav>
        </div>
    </div>
@endsection
