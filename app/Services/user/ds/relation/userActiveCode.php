<?php

namespace App\Services\user\ds\relation;

use Illuminate\Database\Eloquent\Model;

class userActiveCode extends Model
{
    protected $table = '_user_active_code';
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['uid', 'email', 'code', 'send_time', 'active_time', 'typeid'];
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(user::class, 'uid', 'uid');
    }
}