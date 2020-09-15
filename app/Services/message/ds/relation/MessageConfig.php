<?php

namespace App\Services\message\ds\relation;

use Illuminate\Database\Eloquent\Model;
use App\Services\user\ds\relation\user;

class MessageConfig extends Model
{
    protected $table = '_message_config';
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['uid', 'privacy', 'notice_types'];
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(user::class, 'uid', 'uid');
    }

}