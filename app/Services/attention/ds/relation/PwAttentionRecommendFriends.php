<?php

namespace App\Services\attention\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwAttentionRecommendFriends extends Model
{
	protected $table = '_attention_recommend_friends';
	protected $primaryKey = 'uid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['uid', 'recommend_uid', 'recommend_username', 'cnt', 'recommend_user'];
	protected $guarded = [];

}