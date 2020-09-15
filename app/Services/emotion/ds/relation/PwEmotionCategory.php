<?php

namespace App\Services\emotion\ds\relation;

use Illuminate\Database\Eloquent\Model;

class EmotionCategory extends Model
{
	protected $table = '_common_emotion_category';
	protected $primaryKey = 'category_id';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['category_id', 'category_name', 'emotion_folder', 'emotion_apps', 'orderid', 'isopen'];
	protected $guarded = [];

}