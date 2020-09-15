<?php

namespace App\Services\attention\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwFresh extends Model
{
	protected $table = '_attention_fresh';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['id', 'type', 'src_id', 'created_userid', 'created_time'];
	protected $guarded = [];

}