<?php

namespace App\Services\user\ds\relation;

use Illuminate\Database\Eloquent\Model;
use App\Services\usergroup\ds\relation\userGroups;
use App\Services\usergroup\ds\relation\userPermissionGroups;

class userBelong extends Model
{
    protected $table = '_user_belong';
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['uid', 'gid', 'endtime'];
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(user::class, 'uid', 'uid');
    }


    /*public function userGroups(){
        return $this->belongsTo(userGroups::class, 'gid', 'groupid');
    }

    public function userPermissionGroups(){
        return $this->belongsTo(userPermissionGroups::class, 'gid', 'groupid');
    }*/
}