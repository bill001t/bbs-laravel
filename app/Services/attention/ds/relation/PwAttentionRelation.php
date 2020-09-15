<?php

namespace App\Services\attention\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwAttentionRelation extends Model
{
	protected $table = '_attention_type_relations';
	protected $primaryKey = 'uid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['uid', 'touid', 'typeid'];
	protected $guarded = [];

}