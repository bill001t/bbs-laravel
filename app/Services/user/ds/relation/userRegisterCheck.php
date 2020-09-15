<?php

namespace App\Services\user\ds\relation;

use Illuminate\Database\Eloquent\Model;

class userRegisterCheck extends Model
{
    protected $table = '_user_register_check';
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $_dataStruct = ['uid', 'ifchecked', 'ifactived'];
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(user::class, 'uid', 'uid');
    }
}