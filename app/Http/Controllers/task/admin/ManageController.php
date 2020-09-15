<?php
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('SRV:task.dm.PwTaskDm');
Wind::import('SRV:task.dm.PwTaskDmFactory');

/**
 * 任务系统
 *
 * @author xiaoxia.xu <x_824@sina.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: ManageController.php 24028 2013-01-21 03:22:10Z xiaoxia.xuxx $
 * @package src.modules.task
 */
class ManageController extends AdminBaseController {
	
	private $perpage = 10;

	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		$page = intval($request->get('page'));
		$page < 1 && $page = 1;
		/* @var $taskDs PwTask */
		$taskDs = app('task.PwTask');
		$count = $taskDs->countAll();
		$list = array();
		if ($count > 0) {
			$totalPage = ceil($count/$this->perpage);
			$page = $page < 1 ? 1 : ($page > $totalPage ? intval($totalPage) : $page);
			$list = $this->_taskService()->getTaskList($page, $this->perpage);
		}
		->with($count, 'count');
		->with($list, 'list');
		->with($page, 'page');
		->with($this->perpage, 'perpage');
		->with(Core::C('site', 'task.isOpen'), 'isOpen');
	}
	
	/**
	 * 开启操作
	 */
	public function openAction(Request $request) {
		$tasks = $request->get('task');
		$isopen = intval($request->get('isOpen'));
//		if (!$tasks) return $this->showMessage();
		/* @var $taskService PwTaskService */
		$taskService = app('task.srv.PwTaskService');
		foreach ($tasks as $id => $item) {
			$status = isset($item['status']) && $item['status'] == 1 ? 1: 0;
			/*$dm = new PwTaskDm($id);
			$dm->setTitle($item['title'])->setSequence($item['sequence'])->setStatus($status);*/
			$result = $taskService->openTask($id, $status, $item['sequence'], $item['title']);
			if ($result instanceof ErrorBag) return $this->showError($result->getError());
		}
		$config = new PwConfigSet('site');
		$config->set('task.isOpen', $isopen)->flush();
		app('SRV:nav.srv.PwNavService')->updateNavOpen('task', $isopen);
		return $this->showMessage('TASK:edittask.success');
	}

	/**
	 * 添加任务
	 *
	 */
	public function addAction(Request $request) {
		$pre_tasks = $this->_taskService()->getPreTasksByTaskId(0);
		->with($pre_tasks, 'pre_tasks');
		
		//【任务奖励/完成条件】
		/* @var $taskExtends PwTaskExtends */
		$taskExtends = app('APPS:task.service.PwTaskExtends');
		->with($taskExtends->getRewardTypeList(), 'rewardList');
		->with($taskExtends->getConditionTypeList(), 'conditionList');
		//【用户组】
		/* @var $userGroup PwUserGroups */
		$userGroup = app('usergroup.PwUserGroups');
		$groups = $userGroup->getAllGroups();
		$groupTypes = $userGroup->getTypeNames();
		->with($groups, 'groups');
		->with($groupTypes, 'groupTypes');
		->with(Tool::time2str(Tool::getTime(), 'Y-m-d'), '_current');
	}

	/**
	 * 添加任务提交
	 *
	 */
	public function doAddAction(Request $request) {
		$dm = $this->setDm(0);
		if (($r = $this->_taskDs()->addTask($dm)) instanceof ErrorBag) {
			return $this->showError($r->getError());
		}
		return $this->showMessage('TASK:add.task.success', 'task/manage/run');
	}

	/**
	 * 编辑任务
	 *
	 */
	public function editAction(Request $request) {
		//【用户组】
		/* @var $userGroup PwUserGroups */
		$userGroup = app('usergroup.PwUserGroups');
		$groups = $userGroup->getAllGroups();
		$groupTypes = $userGroup->getTypeNames();
		->with($groups, 'groups');
		->with($groupTypes, 'groupTypes');
		
		$id = $this->getTaskId();
		$task = $this->_taskDs()->get($id);
		$task['start_time'] = $task['start_time'] ? Tool::time2str($task['start_time'], 'Y-m-d') : '';
		$task['end_time'] = $task['end_time'] == PwTaskDm::MAXENDTIME ? '' :Tool::time2str($task['end_time'], 'Y-m-d');
		if ($task['user_groups'] == -1) {
			->with(1, 'isAll');
		} else {
			$task['user_groups'] = explode(',', $task['user_groups']);
		}
		
		$task['conditions'] = unserialize($task['conditions']);
		$task['reward'] = unserialize($task['reward']);
		
		//[任务奖励/完成条件]
		/* @var $taskExtends PwTaskExtends */
		$taskExtends = app('APPS:task.service.PwTaskExtends');
		->with($taskExtends->getRewardTypeList($task['reward']), 'rewardList');
		->with($taskExtends->getConditionTypeList($task['conditions']), 'conditionList');
		
		$pre_tasks = $this->_taskService()->getPreTasksByTaskId($id);
		->with($pre_tasks, 'pre_tasks');
		->with($task, 'task');
		->with($groups, 'groups');
		->with(Tool::time2str(Tool::getTime(), 'Y-m-d'), '_current');
	}

	/**
	 * 编辑任务提交
	 *
	 */
	public function doEditAction(Request $request) {
		$id = $this->getTaskId();
		$task = $this->_taskDs()->get($id);
		if (!$task) return $this->showError('TASK:id.illegal');
		$dm = $this->setDm($id);
		$dm->setIsOpen($task['is_open']);
		if (($r = $this->_taskDs()->updateTask($dm)) instanceof ErrorBag) {
			return $this->showError($r->getError());
		}
		if (($dm->getField('icon') != $task['icon']) && $task['icon']) {
			Tool::deleteAttach($task['icon']);
		}
		return $this->showMessage('TASK:edittask.success', 'task/manage/run');
	}

	/**
	 * 删除任务
	 *
	 */
	public function delAction(Request $request) {
		$id = $this->getTaskId();
		if (($r = $this->_taskService()->deleteTask($id)) instanceof ErrorBag) {
			return $this->showError($r->getError());
		}
		return $this->showMessage('TASK:del.success');
	}

	/**
	 * @return PwTaskService
	 */
	private function _taskService() {
		return app('task.srv.PwTaskService');
	}

	/**
	 * @return PwTask
	 */
	private function _taskDs() {
		return app('task.PwTask');
	}

	/**
	 * 设置dm
	 *
	 * @return PwTaskDm
	 */
	private function setDm($id) {
		$condition = $request->get('condition');
		$dm = PwTaskDmFactory::getInstance($condition['type'], $condition['child']);
		PwTaskDmFactory::addRewardDecoration($dm, $request->get('reward'));
		
		$icon = $this->saveIcon();
		$user_groups = $request->get('user_groups');
		$is_display_all = $request->get('is_display_all');
		/*如果全选用户组，则设置该用户组为-1*/
		/* @var $userGroup PwUserGroups */
		$userGroup = app('usergroup.PwUserGroups');
		$groups = $userGroup->getAllGroups();
		if (!$user_groups || !array_diff(array_keys($groups), $user_groups)) {
			$user_groups = array(-1);
		}
		$startTime = $request->get('start_time');
		$endTime = $request->get('end_time');
		$dm->setTaskId($id)->setTitle($request->get('title'))
			->setDescription($request->get('description'))
			->setIcon($icon)
			->setStartTime($startTime ? Tool::str2time($startTime) : 0)
			->setEndTime($endTime ? Tool::str2time($endTime . ' 23:59:59') : PwTaskDm::MAXENDTIME)
			->setPeriod($request->get('period'))
			->setPreTask($request->get('pre_task'))
			->setUserGroups($user_groups)
			->setIsAuto($request->get('is_auto'))
			->setIsDisplayAll($is_display_all)
			->setConditions($condition);
		return $dm;
	}
	
	/**
	 * 获取任务id
	 *
	 * @return int
	 */
	private function getTaskId() {
		$id = intval($request->get('id'));
		->with($id, 'id');
		return $id;
	}
	
	/**
	 * 上传图标
	 *
	 * @return string
	 */
	private function saveIcon() {
		Wind::import("SRV:upload.action.PwTaskIconUpload");
		Wind::import('LIB:upload.PwUpload');
		$taskUpload = new PwTaskIconUpload(80, 80);
		$upload = new PwUpload($taskUpload);
		if (($result = $upload->check()) === true) {
			$result = $upload->execute();
		}
		if ($result !== true) {
			return $this->showError($result->getError());
		}
		$path = $taskUpload->getPath();
		return $path ? $path : $request->get('oldicon');
	}
}