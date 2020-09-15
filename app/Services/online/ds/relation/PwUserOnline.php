<?php

namespace App\Services\online\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwUserOnline extends Model
{
	protected $table = '_online_user';
	protected $primaryKey = 'uid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['uid', 'username', 'modify_time', 'created_time','tid', 'fid', 'gid', 'request'];
	protected $guarded = [];

}