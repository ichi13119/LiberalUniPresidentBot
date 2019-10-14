
@extends('layouts.app')
@section('title', '動画登録画面')
@section('content')
    <div class="container">
      <div class="row">
        <div class="col col-md-offset-3 col-md-6">
          <nav class="panel panel-default">
            <div class="panel-heading">動画を追加する</div>
            <div class="panel-body">
              <form action="{{ route('videos.create') }}" method="post">
                @csrf
                <div class="form-group">
                  <label for="title">タイトル</label>
                  <input type="text" class="form-control" name="title" id="title" />
                </div>
                <div class="form-group">
                  <label for="url">URL</label>
                  <input type="text" class="form-control" name="url" id="url" />
                </div>
                <div class="form-group">
                  <label for="subtitles">字幕</label>
                  <textarea class="form-control" name="subtitles" id="subtitles" rows="10" cols="40" placeholder="文字起こしのコピペ文字列を貼り付け"></textarea>
                </div>
                <div class="text-right">
                  <button type="submit" class="btn btn-primary">送信</button>
                </div>
              </form>
            </div>
          </nav>
        </div>
      </div>
    </div>
    @endsection
