<?php

namespace App\Services\message\ds\relation;

use Illuminate\Database\Eloquent\Model;
use App\Services\user\ds\relation\user;

class MessageNotice extends Model
{
    protected $table = '_message_notice';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['id', 'uid', 'title', 'typeid', 'param','extend_params', 'is_read', 'is_ignore', 'modified_time', 'created_time'];
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(user::class, 'uid', 'id');
    }

}