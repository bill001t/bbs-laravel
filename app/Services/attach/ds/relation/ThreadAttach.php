<?php

namespace App\Services\attach\ds\relation;

use Illuminate\Database\Eloquent\Model;

class ThreadAttach extends Model
{
	protected $table = '_attachs_thread';
	protected $primaryKey = 'aid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['aid', 'fid', 'tid', 'pid', 'name', 'type', 'size', 'hits', 'width', 'height', 'path', 'ifthumb', 'special', 'cost', 'ctype', 'created_userid', 'created_time', 'descrip'];
	protected $guarded = [];


}