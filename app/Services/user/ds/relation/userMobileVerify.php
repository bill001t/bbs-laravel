<?php

namespace App\Services\user\ds\relation;

use Illuminate\Database\Eloquent\Model;

class userMobileVerify extends Model
{
    protected $table = '_user_mobile_verify';
    protected $primaryKey = 'mobile';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['mobile', 'code', 'expired_time', 'number', 'create_time'];
    protected $guarded = [];

    public function userMobile() {
        return $this->belongsTo(userMobile::class, 'mobile', 'mobile');
    }
}