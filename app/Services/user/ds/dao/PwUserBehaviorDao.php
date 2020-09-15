<?php

namespace App\Services\user\ds\dao;

use App\Services\user\ds\relation\userBehavior;
use App\Services\user\ds\traits\userBehaviorTrait;

class PwUserBehaviorDao extends userBehavior
{
	use userBehaviorTrait;

	
	public function getInfo($uid, $behavior) {
		return self::where('uid', $uid)
			->where('behavior', $behavior)
			->get();
	}
	
	public function fetchInfo($uids) {
        return self::whereIn('uid', $uids)
            ->get();
	}
	
	public function getBehaviorList($uid) {
        return self::where('uid', $uid);
	}
	
	public function replaceInfo($data) {
        return self::firstOrCreate($data);
	}
	
	public function deleteInfo($uid) {
        return self::where('uid', $uid)
            ->delete();
	}
	
	public function deleteInfoByUidBehavior($uid, $behavior) {
        return self::where('uid', $uid)
            ->where('behavior', $behavior)
            ->delete();
	}
	
}