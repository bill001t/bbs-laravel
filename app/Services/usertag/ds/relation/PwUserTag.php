<?php

namespace App\Services\usertag\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwUserTag extends Model
{
	protected $table = '_user_tag';
	protected $primaryKey = 'tag_id';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['tag_id', 'name', 'ifhot', 'used_count'];
	protected $guarded = [];
}