<?php

namespace App\Services\nav\ds\relation;

use Illuminate\Database\Eloquent\Model;

class Nav extends Model
{
	protected $table = '_common_nav';
	protected $primaryKey = 'navid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['navid', 'parentid', 'rootid','type', 'sign', 'name', 'style', 'link', 'alt','image', 'target', 'isshow', 'orderid'];
	protected $guarded = [];

}