
@extends('layouts.app')
@section('title', '動画登録画面')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col col-md-offset-3 col-md-6">
                <nav class="panel panel-default">
                    <div class="panel-heading">動画を追加する</div>
                    <div class="panel-body">
                        <form action="{{ $url }}" method="post">
                            @csrf
                            @component('videos.form', [
                                'video'=> $video]
                                )
                            @endcomponent
                        </form>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('#url').change('click',function(){

            $videoId = youtube_parser($(this).val());

            if(!$videoId){
                return;
            }

            $.ajax({
                url:'/api/youtube/getVideoInformation?videoId=' + $videoId,
                type:'GET',
                dataType:"json",
            })
            // Ajaxリクエストが成功した時発動
            .done( (data) => {
                setVideoInformation(data);
            })
            // Ajaxリクエストが失敗した時発動
            .fail( (data) => {
            });
        });

        function youtube_parser(url){
            var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
            var match = url.match(regExp);
            return (match&&match[7].length==11)? match[7] : false;
        }

        function setVideoInformation(data){
            $('#title').val(data['snippet']['title']);
            $('#thumbnail').empty();
            let imageTag = '<img src="' + data['snippet']['thumbnails']['medium']['url'] + '">';
            $('#thumbnail').append(imageTag);
        }
    </script>
@endsection
