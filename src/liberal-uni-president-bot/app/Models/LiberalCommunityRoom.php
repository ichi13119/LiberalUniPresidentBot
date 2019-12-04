<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LiberalCommunityRoom extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'room_id';

    public $incrementing  = false;
}
