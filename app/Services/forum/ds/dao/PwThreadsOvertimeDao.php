<?php

namespace App\Services\forum\ds\dao;

use App\Services\forum\ds\relation\threadsOvertime;
use App\Services\forum\ds\traits\threadsOvertimeTrait;

class PwthreadsOvertimeDao extends threadsOvertime
{
	use threadsOvertimeTrait;

	public function setOvertime($tid,$type,$overtime){
		return self::firstOrCreate([
			'tid' => $tid,
			'm_type' => $type,
			'overtime' => $overtime,
		]);
	}
	
	public function getOvertimeByTid($tid) {
		return self::where('tid', $tid)
			->get();
	}
	
	public function getOvertimeByTidAndType($tid, $type) {
		return self::where('tid', $tid)
			->where('m_type', $type)
			->first();
	}

	public function batchAdd($data) {
		return self::create($data);
	}
		
	public function deleteByTidAndType($tid, $type) {
		return self::where('tid', $tid)
			->where('m_type', $type)
			->delete();
	}
	
	public function batchDelete($ids) {
		return self::destroy($ids);
	}
	
	public function batchDeleteByTidAndType($tids, $type) {
		return self::whereIn('tid', $tids)
			->where('m_type', $type)
			->delete();
	}
}