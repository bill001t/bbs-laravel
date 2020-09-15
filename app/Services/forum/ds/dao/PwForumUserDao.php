<?php

namespace App\Services\forum\ds\dao;

use App\Services\forum\ds\relation\forumUser;
use App\Services\forum\ds\traits\forumUserTrait;

class PwForumUserDao extends forumUser
{
	use forumUserTrait;

	public function get($uid, $fid) {
		return forumUser::where('uid', $uid)
			->where('fid', $fid)
			->first();
	}
	
	public function getUserByFid($fid, $perpage) {
		return forumUser::where('fid', $fid)
			->orderby('join_time', 'desc')
			->paginate($perpage);
	}

	public function countUserByFid($fid) {
		return forumUser::where('fid', $fid)
			->count();
	}

	public function getFroumByUid($uid) {
		return forumUser::where('uid', $uid)
			->get();
	}

	public function add($data) {
		return forumUser::create($data);
	}

	public function _delete($uid, $fid) {
		return forumUser::where('uid', $uid)
			->where('fid', $fid)
			->delete();
	}
}