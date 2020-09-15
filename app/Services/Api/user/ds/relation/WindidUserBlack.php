<?php

namespace App\Services\Api\user\ds\relation;

use Illuminate\Database\Eloquent\Model;

class WindidUserBlack extends Model
{
	protected $table = '_user_black';
	protected $primaryKey = 'uid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['uid', 'blacklist'];
	protected $guarded = [];

}