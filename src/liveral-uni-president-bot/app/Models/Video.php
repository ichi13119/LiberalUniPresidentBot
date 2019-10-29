<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    //
    public function importantWords()
    {
        return $this->hasMany('App\Models\ImportantWord');
    }

}
