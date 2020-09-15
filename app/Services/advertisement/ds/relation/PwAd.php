<?php

namespace App\Services\advertisement\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwAd extends Model
{
	protected $table = '_advertisement';
	protected $primaryKey = 'pid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['pid','identifier','type_id', 'width', 'height', 'status', 'schedule','show_type','condition'];
	protected $guarded = [];


}