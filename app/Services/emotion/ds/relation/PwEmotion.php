<?php

namespace App\Services\emotion\ds\relation;

use Illuminate\Database\Eloquent\Model;

class Emotion extends Model
{
	protected $table = '_common_emotion';
	protected $primaryKey = 'emotion_id';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['emotion_id', 'category_id', 'emotion_name', 'emotion_folder', 'emotion_icon', 'vieworder', 'isused'];
	protected $guarded = [];

}