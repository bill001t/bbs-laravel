<?php

namespace App\Services\user\ds\relation;

use Illuminate\Database\Eloquent\Model;

class userInfo extends Model
{
    protected $table = '_user_info';
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['uid', 'gender','byear', 'bmonth', 'bday', 'location', 'location_text', 'hometown', 'hometown_text', 'homepage', 'qq', 'msn', 'aliww', 'mobile', 'alipay', 'bbs_sign', 'profile', 'regreason', 'telphone', 'address', 'zipcode', 'secret'];
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(user::class, 'uid', 'uid');
    }
}