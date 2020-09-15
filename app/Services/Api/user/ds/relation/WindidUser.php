<?php

namespace App\Services\Api\user\ds\relation;

use Illuminate\Database\Eloquent\Model;

class WindidUser extends Model
{
    protected $table = '_windid_user';
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['uid', 'username', 'email', 'password', 'salt', 'safecv', 'regdate', 'regip'];
    protected $guarded = [];

    public function userData(){
        return $this->hasOne(WindidUserData::class, 'uid', 'uid');
    }

    public function userInfo(){
        return $this->hasOne(WindidUserInfo::class, 'uid', 'uid');
    }
}