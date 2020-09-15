<?php

namespace App\Services\forum\ds\dao;

use App\Services\forum\ds\relation\threadsIndex;
use App\Services\forum\ds\traits\threadsIndexTrait;

use DB;

class PwThreadsIndexDao extends threadsIndex
{
	use threadsIndexTrait;

	public function count() {
		return threadsIndex::where('disabled', 0)
			->count();
	}

	public function countThreadInFids($fids) {
		return threadsIndex::whereIn('fid', $fids)
			->where('disabled', 0)
			->count();
	}

	public function countThreadNotInFids($fids) {
		return threadsIndex::whereNotIn('fid', $fids)
			->where('disabled', 0)
			->count();
	}
	
	public function fetch($perpage, $order) {
		return threadsIndex::where('disabled', 0)
			->orderby($order, 'desc')
			->paginate($perpage);
	}

	public function fetchInFid($fids, $perpage, $order) {
		return threadsIndex::where('disabled', 0)
			->whereIn('fid', $fids)
			->orderby($order, 'desc')
			->paginate($perpage);
	}

	public function fetchNotInFid($fids, $perpage, $order) {
		return threadsIndex::where('disabled', 0)
			->whereNotIn('fid', $fids)
			->orderby($order, 'desc')
			->paginate($perpage);
	}

	public function fetchNotInFidsAndTids($fids, $tids, $perpage, $order) {
		return threadsIndex::where('disabled', 0)
			->whereNotIn('fid', $fids)
			->whereNotIn('tid', $tids)
			->orderby($order, 'desc')
			->paginate($perpage);
	}

	public function addThread($tid, $fields) {
		$fields['tid'] = $tid;
		return threadsIndex::create($fields);
	}

	public function updateThread($tid, $fields) {
		return threadsIndex::where('tid', $tid)
			->update($fields);
	}

	public function batchUpdateThread($tids, $fields) {
		return threadsIndex::whereIn('tid', $tids)
			->update($fields);
	}

	public function revertTopic($tids) {
		return DB::update('UPDATE ' . $this->table . ' a LEFT JOIN pw_bbs_threads b ON a.tid=b.tid SET a.disabled=b.disabled WHERE a.tid IN (?)', [implode(',', $tids)]);
	}
	
	public function deleteThread($tid) {
		return threadsIndex::destroy($tid);
	}

	public function batchDeleteThread($tids) {
		return threadsIndex::destroy($tids);
	}

	public function deleteOver($limit) {
		return threadsIndex::orderby('tid', 'asc')
			->chunk($limit, function($querys){
				$querys->delete();
				/*foreach($querys as $query){
					$query->delete();
				}*/
			});
	}
}