<?php

namespace App\Services\log\ds\relation;

use Illuminate\Database\Eloquent\Model;
use App\Services\user\ds\relation\user;

class LogLogin extends Model
{
    protected $table = '_log_login';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['id', 'uid', 'username', 'typeid', 'created_time', 'ip'];
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(user::class, 'uid', 'id');
    }

}