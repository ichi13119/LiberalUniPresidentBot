<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LiberalCommunityUser extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'account_id';

    public $incrementing  = false;

    protected $fillable = [
        'account_id', 'other',
    ];
}
