<?php

namespace App\Services\tag\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwTagCategoryRelation extends Model
{
	protected $table = '_tag_category_relation';
	protected $primaryKey = 'tag_id';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['tag_id','category_id'];
	protected $guarded = [];

}