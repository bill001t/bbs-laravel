<?php

namespace App\Services\invite\ds\relation;

use Illuminate\Database\Eloquent\Model;

class InviteCode extends Model
{
	protected $table = '_invite_code';
	protected $primaryKey = 'code';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['code', 'created_userid', 'invited_userid', 'ifused', 'created_time', 'modified_time'];
	protected $guarded = [];

}