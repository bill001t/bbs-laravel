<?php

namespace App\Services\Api\message\ds\relation;

use Illuminate\Database\Eloquent\Model;

class MessageRelation extends Model
{
    protected $table = 'message_relation';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['id', 'dialog_id', 'message_id','is_read', 'is_send'];
    protected $guarded = [];


}