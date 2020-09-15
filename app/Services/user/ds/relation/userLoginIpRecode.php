<?php

namespace App\Services\user\ds\relation;

use Illuminate\Database\Eloquent\Model;

class userLoginIpRecode extends Model
{
    protected $table = '_user_login_ip_recode';
    protected $primaryKey = 'ip';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['ip', 'last_time', 'error_count'];

    protected $guarded = [];

}