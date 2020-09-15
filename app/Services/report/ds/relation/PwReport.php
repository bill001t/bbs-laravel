<?php

namespace App\Services\report\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwReport extends Model
{
	protected $table = '_report';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['id', 'type', 'type_id', 'content', 'content_url', 'author_userid', 'created_userid', 'created_time', 'reason', 'ifcheck', 'operate_userid', 'operate_time'];
	protected $guarded = [];

}