<?php

namespace App\Services\space\bs;

use App\Core\ErrorBag;
use App\Services\space\dm\PwSpaceDm;
use App\Services\space\ds\dao\PwSpaceDao;

class PwSpace {
	
	/**
	 * 获取一条记录
	 * 
	 * @param int $uid
	 */
	public function getSpace($uid) {
		$uid = (int)$uid;
		if ($uid < 1) return array();
		return $this->_getDao()->getSpace($uid);
	}
	
	/**
	 * 获取多条记录
	 * 
	 * @param array $uids
	 */
	public function fetchSpace($uids) {
		if (!is_array($uids) || count($uids) < 1 ) return array();
		return $this->_getDao()-> fetchSpace($uids);	
	}
	
	public function getSpaceByDomain($domian) {
		if (empty($domian)) return false;
		return $this->_getDao()->getSpaceByDomain($domian);
	}
	
	public function addInfo($dm) {
		if (!$dm instanceof PwSpaceDm) return new ErrorBag('SPACE:info.error');
		$resource=$dm->beforeAdd();
		if ($resource instanceof ErrorBag) return $resource;
		$data = $dm->getData();
		$data['uid'] = $dm->uid;
		return $this->_getDao()->addInfo($data);
	}
	
	public function updateInfo($dm) {
		if (!$dm instanceof PwSpaceDm) return new ErrorBag('SPACE:info.error');
		$resource=$dm->beforeUpdate();
		if ($resource instanceof ErrorBag) return $resource;
		return $this->_getDao()->updateInfo($dm->uid, $dm->getData());
	}


	public function updateNumber($uid) {
		$uid = (int)$uid;
		return $this->_getDao()->updateNumber($uid);
	}
	
	public function deleteInfo($uid) {
		$uid = (int)$uid;
		if ($uid < 1) return false;
		return $this->_getDao()->deleteInfo($uid);
	}
	
	private function _getDao() {
		return app(PwSpaceDao::class);
	}
}
?>