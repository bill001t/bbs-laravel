<?php

namespace App\Services\forum\ds\dao;

use App\Services\forum\ds\relation\postsTopped;
use App\Services\forum\ds\traits\postsToppedTrait;

class PwPostsToppedDao extends postsTopped
{
	use postsToppedTrait;

	public function getByTid($tid, $perpage) {
		return postsTopped::where('tid', $tid)
			->orderby('created_time', 'desc')
			->paginate($perpage);
	}
	
	public function add($fields){
		return postsTopped::create($fields);
	}
		 
	public function _delete($pid) {
		return postsTopped::destroy($pid);
	}
		 
	public function batchDelete($pids) {
		return postsTopped::destroy($pids);
	}
	
	public function _update($pid,$fields) {
		return postsTopped::where('pid', $pid)
			->update($fields);
	}
}