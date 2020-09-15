<?php

namespace App\Services\usertag\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwUserTagRelation extends Model
{
	protected $table = '_user_tag_relation';
	protected $primaryKey = 'uid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['uid', 'tag_id', 'created_time'];
	protected $guarded = [];
}