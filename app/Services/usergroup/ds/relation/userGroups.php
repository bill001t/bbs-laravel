<?php

namespace App\Services\usergroup\ds\relation;

use Illuminate\Database\Eloquent\Model;
use App\Services\user\ds\relation\user;
use App\Services\user\ds\relation\userBelong;

class userGroups extends Model
{
    protected $table = '_user_groups';
    protected $primaryKey = 'gid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['type', 'name', 'category', 'image', 'points'];
    protected $guarded = [];

    public function user(){
        return $this->hasMany(user::class, 'groupid', 'gid');
    }

    public function userBelong(){
        return $this->hasMany(userBelong::class, 'groupid', 'gid');
    }

    public function userPermissionGroups(){
        return $this->hasOne(userPermissionGroups::class, 'gid', 'gid');
    }
}