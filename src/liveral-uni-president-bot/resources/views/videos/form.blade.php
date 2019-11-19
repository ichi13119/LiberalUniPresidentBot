
    <div class="form-group">
        <label for="url">URL</label>
        <input type="text" class="form-control" name="url" id="url"
                value="{{ $video->url }}"/>
    </div>
    <div class="form-group">
        <label for="title">タイトル</label>
        <input type="text" readonly class="form-control" name="title" id="title"
                value="{{ $video->title }}"/>
    </div>
    <div class="form-group">
        <div id="thumbnail">
        </div>
    </div>
    <div class="form-group">
        <label for="subtitles">字幕</label>
        <textarea class="form-control" name="subtitles" id="subtitles" rows="10" cols="40" placeholder="文字起こしのコピペ文字列を貼り付け">{{ $video->subtitles }}</textarea>
    </div>
    <div class="text-right">
        <button type="submit" class="btn btn-primary">送信</button>
    </div>
