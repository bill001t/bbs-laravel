<?php
namespace App\Services\forum\ds\dao;

use App\Services\forum\ds\relation\threadsBuy;
use App\Services\forum\ds\traits\threadsBuyTrait;

class PwThreadsBuyDao extends threadsBuy
{
	use threadsBuyTrait;

	public function sumCost($tid, $pid) {
		return threadsBuy::select(DB::raw('SUM(cost) AS sum'))
			->where('tid', $tid)
			->where('pid', $pid)
			->get();
	}

	public function get($tid, $pid, $uid) {
		return threadsBuy::where('tid', $tid)
			->where('pid', $pid)
			->where('created_userid', $uid)
			->get();
	}

	public function countByTidAndPid($tid, $pid) {
		return threadsBuy::where('tid', $tid)
			->where('pid', $pid)
			->count();
	}
	
	public function getByTidAndPid($tid, $pid, $perpage) {
		return threadsBuy::where('tid', $tid)
			->where('pid', $pid)
			->orderby('created_time', 'desc')
			->paginate($perpage);
	}
	
	public function getByTidAndUid($tid, $uid) {
		return threadsBuy::where('tid', $tid)
			->where('created_userid', $uid)
			->get();
	}

	public function add($fields) {
		return threadsBuy::create($fields);
	}
}