<?php

namespace App\Services\user\ds\relation;

use Illuminate\Database\Eloquent\Model;

class userBehavior extends Model
{
    protected $table = '_user_behavior';
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['uid', 'behavior', 'number', 'expired_time','extend_info'];
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(user::class, 'uid', 'uid');
    }
}