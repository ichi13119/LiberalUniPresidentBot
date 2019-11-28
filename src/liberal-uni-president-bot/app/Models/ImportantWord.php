<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportantWord extends Model
{
    public function video(){
        return $this->belongsTo('App\Models\Video');
    }
}
