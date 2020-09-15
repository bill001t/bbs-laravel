<?php

namespace App\Services\like\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwLikeLog extends Model
{
	protected $table = '_like_log';
	protected $primaryKey = 'uid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['uid', 'likeid', 'tagids', 'created_time'];
	protected $guarded = [];

}