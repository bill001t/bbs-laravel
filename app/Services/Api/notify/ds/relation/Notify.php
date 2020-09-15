<?php

namespace App\Services\Api\notify\ds\relation;

use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
	protected $table = 'notify';
	protected $primaryKey = 'nid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['nid', 'appid', 'operation', 'param', 'timestamp'];
	protected $guarded = [];


}