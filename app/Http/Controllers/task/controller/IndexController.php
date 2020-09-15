<?php

/**
 * 任务系统前台
 *
 * @author xiaoxia.xu <x_824@sina.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: IndexController.php 23994 2013-01-18 03:51:46Z long.shi $
 * @package src.products.task.controller
 */
class IndexController extends Controller{
	private $perpage = 20;
	
	/* (non-PHPdoc)
	 * @see PwBaseController::beforeAction()
	 */
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if (!$this->loginUser->isExists()) {
			return redirect('u/login/run', array('backurl' => 'task/index/run'));
		}
		if (0 == Core::C('site', 'task.isOpen')) {
			return $this->showError('TASK:app.no.open');
		}
		->with($this->perpage, 'perpage');
	}
	
	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		$page = $this->_getPage();
		/* @var $taskDs PwTaskUser */
		$taskDs = app('task.PwTaskUser');
		$count = $taskDs->countMyTasksByStatus($this->loginUser->uid, 3);
		$list = array();
		if ($count > 0) {
			$totalPage = ceil($count/$this->perpage);
			$page = $page < 1 ? 1 : ($page > $totalPage ? intval($totalPage) : $page);
			/*@var $taskService PwTaskService */
			$taskService = app('task.srv.PwTaskService');
			$list = $taskService->getMyTaskListWithStatu($this->loginUser->uid, 3, $page, $this->perpage);
		}
		->with($count, 'count');
		->with($list, 'list');
		->with($this->_getTaskMode(), 'modes');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:task.index.run.title'), '', '');
		Core::setV('seo', $seoBo);
	}
	
	/**
	 * 展示可以申领的任务列表
	 */
	public function applicableListAction(Request $request) {
		$page = $this->_getPage();
		/*@var $taskService PwTaskService */
		$taskService = app('task.srv.PwTaskService');
		list($count, $list) = $taskService->getApplicableTaskList($this->loginUser->uid, $page, $this->perpage);
		->with($count, 'count');
		->with($list, 'list');
		->with($this->_getTaskMode(), 'modes');
		return view('index_applicable');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:task.index.applicable.title'), '', '');
		Core::setV('seo', $seoBo);
	}
	
	/**
	 * 展示已完成的任务列表
	 */
	public function completeListAction(Request $request) {
		$page = $this->_getPage();
		/* @var $taskDs PwTaskUser */
		$taskDs = app('task.PwTaskUser');
		$count = $taskDs->countMyTasksByStatus($this->loginUser->uid, 4);
		$list = array();
		if ($count > 0) {
			$totalPage = ceil($count/$this->perpage);
			$page = $page < 1 ? 1 : ($page > $totalPage ? intval($totalPage) : $page);
			/*@var $taskService PwTaskService */
			$taskService = app('task.srv.PwTaskService');
			$list = $taskService->getMyTaskListWithStatu($this->loginUser->uid, 4, $page, $this->perpage);
		}
		->with($count, 'count');
		->with($list, 'list');
		return view('index_complete');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:task.index.complete.title'), '', '');
		Core::setV('seo', $seoBo);
	}
	
	/**
	 * 申领任务
	 */
	public function applyTaskAction(Request $request) {
		$id = intval($request->get('id'));
		if ($id < 0) return $this->showError('TASK:id.illegal');
		Wind::import('SRV:task.srv.PwTaskApply');
		/*@var $taskApply PwTaskApply */
		$taskApply = new PwTaskApply($this->loginUser->uid);
		if (($result = $taskApply->apply($id)) instanceof ErrorBag) return $this->showError($result->getError());
		$condition = unserialize($result['conditions']);
		$reward = unserialize($result['reward']);
		$url = $condition['url'] ? $condition['url'] : 'task/index/run';
		//去做任务地址
		$this->addMessage(url($url), 'url');
		//奖励
		$this->addMessage($reward['descript'] ? $reward['descript'] : '无', 'reward');
		//标题
		$this->addMessage($result['title'], 'title');
		//目标
		$this->addMessage($result['description'], 'description');
		//前置任务
		$parent = '';
		if ($result['pre_task']) {
			$pre_task = app('task.PwTask')->get($result['pre_task']);
			$parent['parent'] = $pre_task['title'];
		}
		$this->addMessage($parent, 'pre_task');
		//时限
		$time = '不限';
		if ($result['start_time'] || $result['end_time'] != PwTaskDm::MAXENDTIME) {
			$start_time = $result['start_time'] ? Tool::time2str($result['start_time'], 'Y-m-d') : '不限';
			$end_time = $result['end_time'] == PwTaskDm::MAXENDTIME ? '不限' : Tool::time2str($result['end_time'], 'Y-m-d');
			$time = $start_time . ' 至 ' . $end_time;
		}
		$this->addMessage($time, 'time');
		return $this->showMessage('TASK:apply.success');
	}
	
	/**
	 * 获得奖励
	 */
	public function rewardAction(Request $request) {
		$id = $request->get('id');
		Wind::import('SRV:task.srv.PwTaskGainReward');
		$gainReward = new PwTaskGainReward($this->loginUser->uid, $id);
		if (($result = $gainReward->gainReward()) instanceof ErrorBag) return $this->showError($result->getError());
		$reward = $gainReward->taskInfo['reward']['descript'];
		$this->addMessage($reward ? $reward : '无', 'reward');
		$this->addMessage($gainReward->taskInfo['title'], 'title');
		return $this->showMessage('TASK:gain.task.reward.success');
	}
	
	/**
	 * 获得页数
	 *
	 * @return int
	 */
	private function _getPage() {
		$page = intval($request->get('page'));
		($page < 1) && $page = 1;
		->with($page, 'page');
		->with($this->perpage, 'perpage');
		return $page;
	}
	
	/**
	 * 获得任务的模式
	 *
	 * @return array
	 */
	private function _getTaskMode() {
		$mode = array(1 => array('class' => 'task_mode_end', 'button' => '去做任务'),//已经领取
			2 => array('class' => 'task_mode_expired', 'button' => '已过期'),//已经关闭
			3 => array('class' => 'task_mode_expired', 'button' => '已过期'),//已经过期
			4 => array('class' => 'task_mode_end', 'button' => '继续完成'),//正在进行中
			5 => array('class' => 'task_mode_arrow', 'button' => '领取奖励'));//已完成
		return $mode;
	}
}
?>