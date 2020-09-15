<?php

namespace App\Services\user\ds\relation;

use Illuminate\Database\Eloquent\Model;

class userMobile extends Model
{
    protected $table = '_user_mobile';
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['uid', 'mobile'];
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(user::class, 'uid', 'uid');
    }
}