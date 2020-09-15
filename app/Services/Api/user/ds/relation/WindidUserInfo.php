<?php

namespace App\Services\Api\user\ds\relation;

use Illuminate\Database\Eloquent\Model;

class WindidUserInfo extends Model
{
	protected $table = '_windid_user_info';
	protected $primaryKey = 'uid';
	public $timestamps = false;
	protected $dateFormat = '';
	protected $fillable = ['uid', 'realname','gender', 'byear', 'bmonth', 'bday', 'hometown', 'location', 'homepage', 'qq', 'msn', 'aliww', 'mobile', 'alipay', 'profile'];
	protected $guarded = [];

	public function user() {
		return $this->belongsTo(WindidUser::class, 'uid', 'uid');
	}
}