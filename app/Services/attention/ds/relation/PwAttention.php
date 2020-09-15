<?php

namespace App\Services\attention\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwAttention extends Model
{
	protected $table = '_attention';
	protected $primaryKey = 'uid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['uid', 'touid', 'created_time'];
	protected $guarded = [];

}