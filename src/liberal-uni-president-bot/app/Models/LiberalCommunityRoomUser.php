<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LiberalCommunityRoomUser extends Model
{
    use CompositePrimaryKeyTrait;

    protected $table = 'liberal_community_rooms_users';

    protected $primaryKey = ['account_id', 'room_id'];

    public $incrementing  = false;

    protected $fillable = [
        'account_id', 'room_id', 'enter_date', 'leave_date',
    ];
}
