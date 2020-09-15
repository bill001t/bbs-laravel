<?php

namespace App\Services\draft\ds\relation;

use Illuminate\Database\Eloquent\Model;

class Draft extends Model
{
	protected $table = '_draft';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['id', 'created_userid', 'title', 'content', 'created_time'];
	protected $guarded = [];

	public function hookInject(){
		return $this->hasMany(HookInject::class);
	}
}