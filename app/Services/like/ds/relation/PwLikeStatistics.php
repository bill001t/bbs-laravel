<?php

namespace App\Services\like\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwLikeStatistics extends Model
{
	protected $table = '_like_statistics';
	protected $primaryKey = 'signkey';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['signkey', 'likeid', 'fromid', 'typeid', 'number'];
	protected $guarded = [];

}