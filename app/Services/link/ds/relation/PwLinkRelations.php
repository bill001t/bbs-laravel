<?php

namespace App\Services\link\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwLinkRelation extends Model
{
	protected $table = '_link_relations';
	protected $primaryKey = 'lid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['lid','typeid'];
	protected $guarded = [];

}