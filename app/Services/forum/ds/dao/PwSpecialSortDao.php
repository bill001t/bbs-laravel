<?php
namespace App\Services\forum\ds\dao;

use App\Services\forum\ds\relation\specialsort;
use App\Services\forum\ds\traits\specialsortTrait;

class PwSpecialSortDao extends specialsort
{
	use specialsortTrait;

	public function getSpecialSortByFid($fid) {
		return self::where('fid', $fid)
			->get();
	}
	
	public function getSpecialSortByTid($tid) {
		return specialsort::where('tid', $tid)
			->first();
	}
	
	public function getSpecialSortByTypeExtra($sortType, $extra) {
		return specialsort::where('sort_type', $sortType)
			->where('extra', $extra)
			->get();
	}

	public function batchAdd($data) {
		foreach ($data as $key => $value) {
			specialsort::firstOrNew(array($value['fid'], $value['tid'], intval($value['extra']), $value['sort_type'], $value['created_time'], $value['end_time']));
		}

		specialsort::save();
	}

	public function deleteSpecialSortByTid($tid) {
		return specialsort::where('tid', $tid)
			->delete();
	}

	public function batchDeleteSpecialSortByTid($tids) {
		return specialsort::whereIn('tid', $tids)
			->delete();
	}
}