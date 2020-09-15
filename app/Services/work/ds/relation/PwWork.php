<?php

namespace App\Services\work\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwWork extends Model
{
	protected $table = '_user_work';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['id', 'uid', 'company', 'starty', 'startm', 'endy', 'endm'];
	protected $guarded = [];
}