<?php

namespace App\Services\user\ds\relation;

use Illuminate\Database\Eloquent\Model;

class userBan extends Model
{
    protected $table = '_user_ban';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['id', 'uid', 'typeid', 'fid', 'end_time', 'created_time', 'created_userid', 'reason'];
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(user::class, 'uid', 'uid');
    }

    public function forum() {
        return $this->belongsTo(App\Services\forum\ds\relation\forum::class, 'fid', 'fid');
    }
}