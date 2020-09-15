<?php

namespace App\Services\like\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwLikeTag extends Model
{
	protected $table = '_like_tag';
	protected $primaryKey = 'tagid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['tagid', 'uid', 'tagname','number'];
	protected $guarded = [];

}