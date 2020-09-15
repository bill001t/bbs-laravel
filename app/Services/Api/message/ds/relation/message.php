<?php

namespace App\Services\Api\message\ds\relation;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'message';
    protected $primaryKey = 'message_id';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['message_id', 'from_uid', 'to_uid', 'content', 'created_time'];
    protected $guarded = [];


}