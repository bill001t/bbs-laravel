<?php

namespace App\Services\user\ds\relation;

use Illuminate\Database\Eloquent\Model;

class userRegisterIp extends Model
{
    protected $table = '_user_register_ip';
    protected $primaryKey = 'ip';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['ip', 'last_regdate', 'num'];

    protected $guarded = [];

}