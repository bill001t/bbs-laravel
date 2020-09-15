<?php

namespace App\Services\Api\notify\ds\relation;

use Illuminate\Database\Eloquent\Model;

class NotifyLog extends Model
{
	protected $table = 'notify_log';
	protected $primaryKey = 'logid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['logid', 'nid', 'appid', 'complete', 'send_num', 'reason'];
	protected $guarded = [];


}