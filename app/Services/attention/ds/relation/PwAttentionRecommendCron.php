<?php

namespace App\Services\attention\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwAttentionRecommendCron extends Model
{
	protected $table = '_attention_recommend_cron';
	protected $primaryKey = 'uid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['uid', 'created_time'];
	protected $guarded = [];

}