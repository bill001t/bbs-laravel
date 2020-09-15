<?php

namespace App\Services\like\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwLikeRelations extends Model
{
	protected $table = '_like_tag_relations';
	protected $primaryKey = 'logid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['logid', 'tagid'];
	protected $guarded = [];

}