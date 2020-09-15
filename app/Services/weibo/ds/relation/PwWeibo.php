<?php

namespace App\Services\weibo\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwWeibo extends Model
{
	protected $table = '_weibo';
	protected $primaryKey = 'weibo_id';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['weibo_id', 'src_id', 'content', 'type', 'comments', 'extra', 'like_count', 'created_userid', 'created_username', 'created_time'];
	protected $guarded = [];

}