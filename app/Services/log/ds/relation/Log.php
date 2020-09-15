<?php

namespace App\Services\log\ds\relation;

use Illuminate\Database\Eloquent\Model;
use App\Services\user\ds\relation\user;

class Log extends Model
{
    protected $table = '_log';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['id', 'typeid', 'created_userid', 'created_time', 'operated_uid', 'created_username', 'operated_username', 'ip', 'fid', 'tid', 'pid', 'extends', 'content'];
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(user::class, 'uid', 'id');
    }

}