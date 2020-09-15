<?php
Wind::import('ADMIN:library.AdminBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PermissionsController.php 28818 2013-05-24 10:10:46Z gao.wanggao $ 
 * @package 
 */
class PermissionsController extends AdminBaseController {
	
	public function run() {
		$username = $request->get('username','post');
		$ds = $this->_getPermissionsDs();
		if ($username) {
			$user = app('user.PwUser')->getUserByName($username);
			$uid = isset($user['uid']) ? $user['uid'] : 0;
			if ($uid < 1)  return $this->showError("permission.design.uid.empty");
		} 
		Wind::import('SRV:design.srv.vo.PwDesignPermissionsSo');
		$vo = new PwDesignPermissionsSo();
		if ($uid) $vo->setUid($uid);
		$_tmp = $ds->searchPermissions($vo);
		$_gids = $_uids = array();
		foreach ($_tmp AS $v) $_uids[] = $v['uid'];
		array_unique($_uids);
		$users = app('user.PwUser')->fetchUserByUid($_uids, PwUser::FETCH_MAIN);
		foreach ($users AS &$user) {
			$user['gid'] =  ($user['groupid'] == 0) ? $user['memberid'] : $user['groupid'];
			$_gids[] = $user['gid'];
		}
		array_unique($_gids);
		$groups = app('usergroup.PwUserGroups')->fetchGroup($_gids);
		->with($users, 'users');
		->with($groups, 'groups');
	}
	
	public function viewAction(Request $request) {
		$uid = (int)$request->get('uid','get');
		if ($uid < 1) return $this->showError("permission.design.uid.empty");
		Wind::import('SRV:design.srv.vo.PwDesignPermissionsSo');
		$vo = new PwDesignPermissionsSo();
		$vo->setUid($uid);
		$list = $this->_getPermissionsDs()->searchPermissions($vo);
		$_ids = array();
		foreach ($list AS $v) {
			$_ids[$v['design_type']][$v['id']] = $v['design_id'];
		}
		foreach ($_ids AS $k=>$ids) {
			if ($k == PwDesignPermissions::TYPE_PAGE) {
				$info = $this->_getPageDs()->fetchPage($ids);	
				foreach ($ids AS $_k=>$id) {
					$list[$_k]['type'] = '页面';
					$list[$_k]['name'] = $info[$id]['page_name'];
					$list[$_k]['url'] = url('design/permissions/page', array('id' => $info[$id]['page_id']));
				}
			} 
			if ($k == PwDesignPermissions::TYPE_MODULE) {
				$info = $this->_getModuleDs()->fetchModule($ids);
				foreach ($ids AS $_k=>$id) {
					$list[$_k]['type'] = '模块';
					$list[$_k]['name'] = $info[$id]['module_name'];
					$list[$_k]['url'] = url('design/permissions/module', array('moduleid' => $info[$id]['module_id']));
				}
			}
			/*
			if ($k == PwDesignPermissions::TYPE_PORTAL) {
				$info = $this->_getPageDs()->fetchPage($ids);	
				foreach ($ids AS $_k=>$id) {
					$list[$_k]['type'] = '页面';
					$list[$_k]['name'] = $info[$id]['page_name'];
					$list[$_k]['url'] = url('design/permissions/page', array('id' => $info[$id]['page_id']));
				}
			}*/
		}
		$user = app('user.PwUser')->getUserByUid($uid);
		$user['gid'] =  ($user['groupid'] == 0) ? $user['memberid'] : $user['groupid'];
		$group = app('usergroup.PwUserGroups')->getGroupByGid($user['gid']);
		$user['groupname'] = $group['name'];
		->with($list, 'list');
		->with($user, 'user');
	}
	
	public function pageAction(Request $request) {
		$uids = array();
		$designId = (int)$request->get('id', 'get');
		$pageInfo = $this->_getPageDs()->getPage($designId);
		if (!$pageInfo) return $this->showError("operate.fail");
		$ds = $this->_getPermissionsDs();
		$type = ($pageInfo['page_type'] == PwDesignPage::PORTAL) ? 0 : 1;
		Wind::import('SRV:design.srv.vo.PwDesignPermissionsSo');
		$vo = new PwDesignPermissionsSo();
		$vo->setDesignType(PwDesignPermissions::TYPE_PAGE)
		   ->setDesignId($designId); 
		$list = $ds->searchPermissions($vo);
		foreach ($list AS $v) {
			$uids[] = $v['uid'];
		}
		$users = app('user.PwUser')->fetchUserByUid($uids, PwUser::FETCH_MAIN);
		->with($list,'list');
		->with($users,'users');
		->with($designId,'designId');
		->with($type, 'type');
		->with(PwDesignPermissions::TYPE_PAGE, 'pType');
		
		->with($this->_getPageDs()->getPage($designId),'info');
	}
	
	public function moduleAction(Request $request) {
		$uids = array();
		$designId = (int)$request->get('moduleid', 'get');
		if ($designId < 1) return $this->showError("operate.fail");
		$ds = $this->_getPermissionsDs();
		Wind::import('SRV:design.srv.vo.PwDesignPermissionsSo');
		$vo = new PwDesignPermissionsSo();
		$vo->setDesignType(PwDesignPermissions::TYPE_MODULE)
		   ->setDesignId($designId); 
		$list = $ds->searchPermissions($vo);
		foreach ($list AS $v) {
			$uids[] = $v['uid'];
		}
		$users = app('user.PwUser')->fetchUserByUid($uids, PwUser::FETCH_MAIN);
		$moduleinfo = $this->_getModuleDs()->getModule($designId);
		->with($list,'list');
		->with($users,'users');
		->with($designId,'designId');
		->with(PwDesignPermissions::TYPE_MODULE, 'pType');
		->with($moduleinfo,'info');
	}

	
	public function doeditAction(Request $request) {
		$designId = (int)$request->get('design_id', 'post');
		$designType = (int)$request->get('design_type', 'post');
		$new_permissions = $request->get('new_permissions', 'post');
		$new_username = $request->get('new_username', 'post');
		$ids = $request->get('ids', 'post');
		$permissions = $request->get('permissions', 'post');
		$fail = 0;
		$ds = $this->_getPermissionsDs();
		//添加新用户  前端已修改为单用户提交
		if ($new_username){
			Wind::import('SRV:design.srv.vo.PwDesignPermissionsSo');
			Wind::import('SRV:user.bo.PwUserBo');
			$service = $this->_getPermissionsService();
			foreach ($new_username AS $k=>$name) {
				if (!$name) continue;
				$user = app('user.PwUser')->getUserByName($name);
				$new_uid = isset($user['uid'])? $user['uid'] : 0;
				if ($new_uid < 1) return $this->showError("DESIGN:user.name.error");
				$vo = new PwDesignPermissionsSo();
				$vo->setDesignId($designId)
					->setDesignType($designType)
					->setUid($new_uid);
				$list = $ds->searchPermissions($vo);
				if ($list) return $this->showError("DESIGN:user.already.permissions");
				if ($service->getPermissionsForUserGroup($new_uid) < 0 ) return $this->showError("DESIGN:user.group.error");
				$userBo = new PwUserBo($new_uid);
				$designPermission = $userBo->getPermission('design_allow_manage.push');
				if ($designPermission < 1) return $this->showError("DESIGN:user.group.error");
				$resource = $ds->addInfo($designType, $designId, $new_uid, $new_permissions[$k]);
				if (!$resource) $fail++;
			}
		}
		foreach ($ids AS $k=>$id) {
			$resource = $ds->updatePermissions($id, $permissions[$k]);
			if (!$resource) $fail++;
		} 
		return $this->showMessage("operate.success");
	}
	
	public function deleteAction(Request $request) {
		$id = (int)$request->get('id', 'post');
		$ds = $this->_getPermissionsDs();
		$info = $ds->getInfo($id);
		if (!$info) return $this->showError("operate.fail");
		$ds->deleteInfo($id);
		return $this->showMessage("operate.success");
	}
	
	public function batchdeleteAction(Request $request) {
		$deleteIds = $request->get('del_ids', 'post');
		$resource = $this->_getPermissionsDs()->batchDelete($deleteIds);
		return $this->showMessage("operate.success");
	}
	
	private function _getPermissionsService() {
		return app('design.srv.PwDesignPermissionsService');
	}
	
	private function _getPermissionsDs() {
		return app('design.PwDesignPermissions');
	}
	
	private function _getPageDs() {
		return app('design.PwDesignPage');
	}
	
	
	private function _getPortalDs() {
		return app('design.PwDesignPortal');
	}
	
	private function _getModuleDs() {
		return app('design.PwDesignModule');
	}
}