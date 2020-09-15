<?php

namespace App\Services\attention\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwAttentionRecommendRecord extends Model
{
	protected $table = '_attention_recommend_record';
	protected $primaryKey = 'uid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['uid', 'recommend_uid', 'same_uid'];
	protected $guarded = [];

}