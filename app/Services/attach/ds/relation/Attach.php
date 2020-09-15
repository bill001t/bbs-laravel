<?php

namespace App\Services\attach\ds\relation;

use Illuminate\Database\Eloquent\Model;

class Attach extends Model
{
	protected $table = '_attachs';
	protected $primaryKey = 'aid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['aid', 'name', 'type', 'size', 'path', 'ifthumb', 'created_userid', 'created_time', 'app', 'descrip'];
	protected $guarded = [];


}