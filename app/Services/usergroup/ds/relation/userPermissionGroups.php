<?php

namespace App\Services\usergroup\ds\relation;

use Illuminate\Database\Eloquent\Model;
use App\Services\user\ds\relation\user;
use App\Services\user\ds\relation\userBelong;

class userPermissionGroups extends Model
{
    protected $table = '_user_permission_groups';
    protected $primaryKey = 'gid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['gid', 'rkey', 'rtype', 'rvalue', 'vtype'];
    protected $guarded = [];

    public function user(){
        return $this->hasMany(user::class, 'groupid', 'gid');
    }

    public function userBelong(){
        return $this->hasMany(userBelong::class, 'groupid', 'gid');
    }

    public function userGroups(){
        return $this->belongsTo(userGroups::class, 'gid', 'gid');
    }
}