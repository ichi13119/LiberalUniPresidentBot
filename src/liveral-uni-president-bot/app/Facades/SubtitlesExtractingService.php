<?php
namespace App\Facades;
use Illuminate\Support\Facades\Facade;
class SubtitlesExtractingService extends Facade
{
    protected static function getFacadeAccessor() {
        return 'SubtitlesExtractingService';
    }
}