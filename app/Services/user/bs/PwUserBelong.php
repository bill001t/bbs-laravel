<?php

namespace App\Services\user\bs;

use App\Core\ErrorBag;
use App\Services\user\ds\dao\PwUserBelongDao;

/**
 * 用户所属组信息表
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: PwUserBelong.php 11842 2012-06-13 12:08:19Z jieyin $
 * @package src.service.user
 */
class PwUserBelong {
	
	/**
	 * 根据用户Id获得该用户拥有的用户组
	 *
	 * @param int $uid
	 * @return array
	 */
	public function getUserBelongs($uid) {
		if (!$uid) return array();
		return $this->_getDao()->getByUid($uid);
	}

	public function getUserByGid($gid) {
		if (!$gid) return array();
		return $this->_getDao()->getByGid($gid);
	}
	
	/**
	 * 根据用户ID列表批量获得这些用户的拥有组
	 *
	 * @param array $uids 用户ID列表
	 * @return array
	 */
	public function fetchUserByUid($uids) {
		if (!$uids) return array();
		return $this->_getDao()->fetchUserByUid($uids);
	}
	
	/**
	 * 根据用户ID删除信息
	 *
	 * @param int $uid
	 * @return boolean
	 */
	public function deleteByUid($uid) {
		if (!($uid = intval($uid))) return false;
		return $this->_getDao()->deleteByUid($uid);
	}
	
	/**
	 * 根据用户ID列表删除记录信息
	 *
	 * @param array $uids
	 * @return boolean
	 */
	public function batchDeleteByUids($uids) {
		if (!$uids) return false;
		return $this->_getDao()->batchDeleteByUids($uids);
	}
	
	/**
	 * 根据用户ID更新用户关联用户信息
	 *
	 * @param int $uid
	 * @param array $$belongs
	 * @return ErrorBag|boolean
	 */
	public function update($uid, $belongs) {
		if (($uid = intval($uid)) < 1) return (new ErrorBag('USER:error.uid'))->get();
		if (!$belongs) return new ErrorBag('USER:error.format');
		return $this->_getDao()->edit($uid, $belongs);
	}
		
	/**
	 * 获得用户所属用户组DAo
	 *
	 * @return PwUserBelongDao
	 */
	private function _getDao() {
		return app(PwUserBelongDao::class);
	}
}