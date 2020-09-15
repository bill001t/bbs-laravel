<?php

namespace App\Services\attention\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwAttentionType extends Model
{
	protected $table = '_attention_type';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['id', 'uid', 'name'];
	protected $guarded = [];

}