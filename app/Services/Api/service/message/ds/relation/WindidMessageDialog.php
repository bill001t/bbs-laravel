<?php

namespace App\Services\Api\message\ds\relation;

use Illuminate\Database\Eloquent\Model;

class WindidMessageDialog extends Model
{
    protected $table = '_message_dialog';
    protected $primaryKey = 'dialog_id';
    public $timestamps = false;
    protected $dateFormat = '';
    protected $fillable = ['dialog_id', 'to_uid', 'from_uid', 'unread_count', 'message_count', 'last_message', 'modified_time'];
    protected $guarded = [];
}