<?php

namespace App\Services\attention\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwFreshRelation extends Model
{
	protected $table = '_attention_fresh_relations';
	protected $primaryKey = 'uid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['uid', 'fresh_id', 'type', 'created_userid', 'created_time'];
	protected $guarded = [];

}