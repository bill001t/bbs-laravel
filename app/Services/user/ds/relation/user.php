<?php

namespace App\Services\user\ds\relation;

use Illuminate\Database\Eloquent\Model;
use App\Services\usergroup\ds\relation\userGroups;
use App\Services\usergroup\ds\relation\userPermissionGroups;
use App\Services\log\ds\relation\logLogin;
use App\Services\log\ds\relation\log;
use App\Services\message\ds\relation\MessageConfig;
use App\Services\message\ds\relation\MessageNotice;

class user extends Model
{
    protected $table = '_user';
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['uid', 'username', 'email', 'password', 'regdate', 'realname', 'status', 'groupid', 'memberid', 'groups'];
    protected $guarded = [];

    public function userActiveCode(){
        return $this->hasMany(userActiveCode::class, 'uid', 'uid');
    }

    public function userBan(){
        return $this->hasMany(userBan::class, 'uid', 'uid');
    }

    public function userBehavior(){
        return $this->hasMany(userBehavior::class, 'uid', 'uid');
    }

    public function userBelong(){
        return $this->hasOne(userBelong::class, 'uid', 'uid');
    }

    public function userData(){
        return $this->hasOne(userData::class, 'uid', 'uid');
    }

    public function userEducation(){
        return $this->hasOne(userEducation::class, 'uid', 'uid');
    }

    public function userGroups(){
        return $this->belongsTo(userGroups::class, 'gid', 'groupid');
    }

    public function userPermissionGroups(){
        return $this->belongsTo(userPermissionGroups::class, 'gid', 'groupid');
    }

    public function userInfo(){
        return $this->hasOne(userInfo::class, 'uid', 'uid');
    }

    public function userMobile(){
        return $this->hasMany(userMobile::class, 'uid', 'uid');
    }

    public function userRegisterCheck(){
        return $this->hasMany(userRegisterCheck::class, 'uid', 'uid');
    }

    public function userWork(){
        return $this->hasMany(userWork::class, 'uid', 'uid');
    }

    public function log(){
        return $this->hasMany(Log::class, 'id', 'uid');
    }

    public function logLogin(){
        return $this->hasMany(logLogin::class, 'id', 'uid');
    }

    public function MessageConfig(){
        return $this->hasMany(MessageConfig::class, 'uid', 'uid');
    }

    public function MessageNotice(){
        return $this->hasMany(MessageNotice::class, 'id', 'uid');
    }
}