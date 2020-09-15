<?php

namespace App\Services\like\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwLikeSource extends Model
{
	protected $table = '_like_source';
	protected $primaryKey = 'sid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['sid', 'subject', 'source_url', 'from_app', 'fromid', 'like_count'];
	protected $guarded = [];

}