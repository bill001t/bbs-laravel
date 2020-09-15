<?php

namespace App\Services\weibo\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwWeiboComment extends Model
{
	protected $table = '_weibo_comment';
	protected $primaryKey = 'comment_id';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['comment_id', 'weibo_id', 'content', 'extra', 'created_userid', 'created_username', 'created_time'];
	protected $guarded = [];

}