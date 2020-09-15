<?php

namespace App\Services\seo\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwSeo extends Model
{
	protected $table = '_seo';
	protected $primaryKey = 'mod';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['mod', 'page', 'param', 'title', 'keywords', 'description'];
	protected $guarded = [];

}