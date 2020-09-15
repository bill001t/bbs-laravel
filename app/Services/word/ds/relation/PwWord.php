<?php

namespace App\Services\word\ds\relation;

use Illuminate\Database\Eloquent\Model;

class PwWord extends Model
{
	protected $table = '_word';
	protected $primaryKey = 'word_id';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['word_id', 'word_type', 'word', 'word_replace', 'word_from', 'created_time'];
	protected $guarded = [];
}