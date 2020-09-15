<?php

namespace App\Services\like\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwLikeContent extends Model
{
	protected $table = '_like_content';
	protected $primaryKey = 'likeid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['likeid', 'typeid', 'fromid', 'isspecial', 'users', 'reply_pid'];
	protected $guarded = [];

}