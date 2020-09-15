<?php

namespace App\Services\online\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwOnlineStatistics extends Model
{
	protected $table = '_online_statistics';
	protected $primaryKey = 'signkey';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['signkey', 'number', 'created_time'];
	protected $guarded = [];

}