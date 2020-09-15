<?php
Wind::import('ADMIN:library.AdminBaseController');

/**
 * 后台用户组管理文件
 * 
 * @author peihong.zhangph <peihong.zhangph@aliyun-inc.com> Nov 4, 2011
 * @link http://www.phpwind.com
 * @copyright 2011 phpwind.com
 * @license
 * @version $Id: GroupsController.php 28860 2013-05-28 03:16:49Z jieyin $
 */

class GroupsController extends AdminBaseController {

	/**
	 * 后台首页处理方法
	 */
	public function run() {
		//TODO 后台默认首页内容扩展支持
		list($groupType) = $request->get(array('type'), 'get');
		$groupType or $groupType = 'member';
		$groups = $this->_getGroupDs()->getGroupsByTypeInUpgradeOrder($groupType);
		if ('member' == $groupType) {
			$points = array();
			$last = '';
			foreach ($groups as $gid => $_item) {
				$points[] = $_item['points']; 
				$last = $_item['points'];
			}
			$points[] = 999999999;
			->with($points, 'points');
		}
		
		//用户组类型
		$groupTypes = $this->_getGroupDs()->getGroupTypes();
		$typeClasses = array();
		foreach ($groupTypes as $v){
			$typeClasses[$v] = $groupType == $v ? ' class="current"' : '';//TODO
		}
		//level images
		$imageDir = Wind::getRealDir('PUBLIC:res.images.level') . DIRECTORY_SEPARATOR;
		//$imageDir = sprintf('%s/www/res/images/level/',dirname(rtrim(WEKIT_PATH,DIRECTORY_SEPARATOR)));
		$imageUrl = sprintf('%s/level',Core::url()->images);
		$imageFiles = array();
		if (is_dir($imageDir)) {
		    if (false !== ($dh = opendir($imageDir))) {
		        while (($file = readdir($dh)) !== false) {
		        	if (filetype($imageDir . $file) == 'dir') continue;
		            $imageFiles[] = $file;
		        }
		        closedir($dh);
		    }
		}
		natcasesort($imageFiles);
		->with($imageUrl, 'imageUrl');
		->with($imageFiles, 'imageFiles');
		->with($groupType, 'groupType');
		->with($typeClasses, 'typeClasses');
		->with($groups, 'groups');
	}
	
	/**
	 * 编辑用户组
	 */
	public function editAction(Request $request){
		list($gid, $category, $isManage) = $request->get(array('gid', 'category', 'manage'), 'get');
		settype($isManage,'boolean');

		//权限分类
		$topLevelCategories = $this->_getPermissionService()->getTopLevelCategories($isManage);
		$category or $category = key($topLevelCategories);
		$permissionConfigs = $topLevelCategoryClasses = array();
		foreach ($topLevelCategories as $k => $v) {
			$topLevelCategoryClasses[$k] = $category == $k ? ' class="current"' : '';//TODO
			$permissionConfigs[$k] = $this->_getPermissionService()->getPermissionConfigByCategory($gid, $k);
		}
		//group info
		$group = $this->_getGroupDs()->getGroupByGid($gid);
		$groupTypes = $isManage ? array('system','special') : array('member','default','system','special');
		$groups = array();
		foreach ($groupTypes as $v) {
			$groups += $this->_getGroupDs()->getGroupsByTypeInUpgradeOrder($v);
		}
		->with($groups, 'groups');
		->with($gid, 'gid');
		->with($isManage, 'isManage');
		->with($category, 'category');
		->with($group, 'group');
		->with($topLevelCategoryClasses, 'topLevelCategoryClasses');
		->with($topLevelCategories, 'topLevelCategories');
		->with($permissionConfigs, 'permissionConfigs');
	}
	
	/**
	 * 保存用户组权限设置
	 */
	public function doeditAction(Request $request){
		$request->isPost() || return $this->showError('operate.fail');

		list($mainGid, $category, $gpermission, $groupname) = $request->get(array('gid', 'category', 'gpermission', 'groupname'), 'post');
		$permissionService = app('usergroup.PwUserPermission');
		Wind::import('SRV:usergroup.dm.PwUserPermissionDm');

		$isManage = stripos($category, 'manage_') === 0;
		$permissionKeys = $this->_getPermissionService()->getPermissionKeys($isManage);
		//$deleteKeys = array();
		//copy groups
		list($copyGroups,$copyItems) = $request->get(array('copy_groups','copy_items'), 'post');
		$gids = array($mainGid);
		$copyGroups && $gids = array_merge($gids, $copyGroups);
		foreach ($gids as $gid) {
			$flag = $mainGid == $gid;
			$permissionModel = new PwUserPermissionDm($gid);
			foreach ($permissionKeys as $k) {
				if (!$flag && !isset($copyItems[$k])) continue;
				if (isset($gpermission[$k])) {
					$permissionModel->setPermission($k, $gpermission[$k]);
				} else {
					//$deleteKeys[] = $k;
					$permissionModel->setPermission($k, '');
				}
			}
			$permissionService->setPermission($permissionModel);
	
			//group info
			if ($flag) {
				$group = $this->_getGroupDs()->getGroupByGid($gid);
				if ($groupname && $groupname != $group['name']){
					Wind::import('SRV:usergroup.dm.PwUserGroupDm');
					$dm = new PwUserGroupDm($gid);
					$dm->setGroupName($groupname);
					$this->_getGroupDs()->updateGroup($dm);
				}
			}
		}
		//$deleteKeys && $permissionService->batchDeletePermissionByGidAndKeys($gid,$deleteKeys);
		return $this->showMessage('USER:groups.permission.edit.success','u/groups/edit/?gid=' . $mainGid . '&manage=' . intval($isManage),true);
	}

	public function setrightAction(Request $request) {
		$rkey = $request->get('rkey', 'get');
		
		$typeName = $this->_getGroupDs()->getTypeNames();
		$configs = $this->_getPermissionService()->getPermissionConfig();
		$permission = $configs[$rkey];
		$groupPermissions = $this->_getPermissionDs()->getPermissionByRkey($rkey);
		
		$permissionConfigs = array();
		$groups = $this->_getGroupDs()->getAllGroups();
		foreach ($groups as $key => $value) {
			if ($permission['1'] != 'basic' && in_array($value['type'], array('member', 'default'))) {
				continue;
			}
			$defaultValue = isset($groupPermissions[$key]) ? $groupPermissions[$key]['rvalue'] : null;

			$permissionConfigs[$value['type']][$value['gid']] = array(
				'name' => $value['name'],
				'default' => $defaultValue,
				'config' => $permission
			);
		}

		->with($rkey, 'rkey');
		->with($permission, 'permission');
		->with($permission[1] == 'basic' ? 0 : 1, 'manage');
		->with($typeName, 'typeName');
		->with($permissionConfigs, 'permissionConfigs');
	}

	public function dosetrightAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');
		list($rkey, $gpermission) = $request->get(array('rkey', 'gpermission'), 'post');
		
		$permissionService = app('usergroup.PwUserPermission');
		Wind::import('SRV:usergroup.dm.PwUserPermissionDm');

		foreach ($gpermission as $key => $value) {
			$dm = new PwUserPermissionDm($key);
			$dm->setPermission($rkey, $value);
			$permissionService->setPermission($dm);
		}
		return $this->showMessage('USER:groups.permission.edit.success','u/groups/setright/?rkey=' . $rkey, true);
	}
	
	/**
	 * 保存用户组信息
	 */
	public function dosaveAction(Request $request){
		$request->isPost() || return $this->showError('operate.fail');
		list($groupType, $groupName, $groupPoints, $groupImage, $newGroupName, $newGroupPoints, $newGroupImage) = $request->get(array('grouptype', 'groupname', 'grouppoints', 'groupimage', 'newgroupname', 'newgrouppoints', 'newgroupimage'), 'post');
		
		$userGroupService = app('usergroup.PwUserGroups'); /* @var $userGroupService PwUserGroups */
		Wind::import('SRV:usergroup.dm.PwUserGroupDm');
		
		is_array($groupName) || $groupName = array();
		is_array($groupPoints) || $groupPoints = array();
		is_array($groupImage) || $groupImage = array();

		if ('member' == $groupType) {
			$_allPointTmp = array_merge($groupPoints, (array)$newGroupPoints);
			if (count($_allPointTmp) != count(array_unique($_allPointTmp))) {
				return $this->showError('USER:groups.info.save.points.fail');
			}
		}
		
		//保存已有用户组
		$updateGroups = array(); //待更新用户组Dm
		foreach ($groupName as $k => $v) {
			$userGroupModel = new PwUserGroupDm($k);
			$userGroupModel->setGroupName($v);
			$userGroupModel->setGroupPoints($groupPoints[$k]);
			$userGroupModel->setGroupImage($groupImage[$k]);
			$userGroupModel->setGroupType($groupType);
			$result = $userGroupModel->beforeUpdate();
			if ($result instanceof ErrorBag) {
				return $this->showError($result->getError());
			}
			$updateGroups[] = $userGroupModel;
		}
		
		//新增用户组
		$addGroups = array(); //待添加用户组Dm
		foreach ($newGroupName as $k => $v) {
			if (!$v) continue;
			$userGroupModel = new PwUserGroupDm();
			$userGroupModel->setGroupName($v);
			$userGroupModel->setGroupPoints($newGroupPoints[$k]);
			$userGroupModel->setGroupImage($newGroupImage[$k]);
			$userGroupModel->setGroupType($groupType);
			$result = $userGroupModel->beforeAdd();
			if ($result instanceof ErrorBag) {
				return $this->showError($result->getError());
			}
			$addGroups[] = $userGroupModel;
		}
		
		//执行更新
		$userGroupService->updateGroups($updateGroups);

		//执行新增
		foreach ($addGroups as $v) {
			$userGroupService->addGroup($v);
		}
		
		return $this->showMessage('USER:groups.info.save.success','u/groups/run');
		//return redirect('u/groups/run'));
	}
	
	/**
	 * 删除用户组
	 */
	public function deleteAction(Request $request){
		list($gid) = $request->get(array('gid'), 'post');
		if ($gid <= 7) {
			return $this->showError('USER:groups.delete.error.default');
		}
		$userGroupService = app('usergroup.PwUserGroups');
		$permissionService = app('usergroup.PwUserPermission');
		$permissionService->deletePermissionsByGid($gid);
		$userGroupService->deleteGroup($gid);
		
		return $this->showMessage('USER:groups.delete.success');
		//return redirect('index.php?&m=u&c=groups&a=run');
	}
	
	/**
	 * @return PwPermissionService
	 */
	private function _getPermissionService(){
		return app('usergroup.srv.PwPermissionService');
	}
	
	/**
	 * @return PwUserPermission
	 */
	private function _getPermissionDs(){
		return app('usergroup.PwUserPermission');
	}
	
	/**
	 * @return PwUserGroups
	 */
	private function _getGroupDs(){
		return app('usergroup.PwUserGroups');
	}
}