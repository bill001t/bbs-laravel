<?php

namespace App\Services\remind\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwRemind extends Model
{
	protected $table = '_remind';
	protected $primaryKey = 'uid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['uid', 'touid'];
	protected $guarded = [];

}