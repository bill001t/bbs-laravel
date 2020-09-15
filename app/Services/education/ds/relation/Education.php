<?php

namespace App\Services\education\ds\relation;

use Illuminate\Database\Eloquent\Model;

class UserEducation extends Model
{
	protected $table = '_user_education';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['id', 'uid', 'schoolid', 'degree', 'start_time'];
	protected $guarded = [];

}