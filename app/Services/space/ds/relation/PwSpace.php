<?php

namespace App\Services\space\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwSpace extends Model
{
	protected $table = '_space';
	protected $primaryKey = 'uid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['uid', 'space_name', 'space_descrip', 'space_domain', 'space_style', 'back_image', 'visit_count','space_privacy', 'visitors', 'tovisitors'];
	protected $guarded = [];

}