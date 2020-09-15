<?php

namespace App\Services\attention\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwFreshIndex extends Model
{
	protected $table = '_attention_fresh_index';
	protected $primaryKey = 'fresh_id';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['fresh_id', 'tid'];
	protected $guarded = [];

}