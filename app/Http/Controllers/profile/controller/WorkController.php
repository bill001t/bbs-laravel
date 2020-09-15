<?php
Wind::import('APPS:.profile.controller.BaseProfileController');
Wind::import('SRV:work.dm.PwWorkDm');

/**
 * 用户资料-工作经历扩展
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: WorkController.php 28852 2013-05-28 02:46:06Z jieyin $
 * @package src.productions.u.controller.profile
 */
class WorkController extends BaseProfileController {
	protected $number = 10;

	/* (non-PHPdoc)
	 * @see BaseExtendsInjector::run()
	 */
	public function run() {
		$page = abs(intval($request->get('page')));
		($page < 1) && $page = 1;
		$count = $this->_getDs()->countByUid($this->loginUser->uid);
		$list = array();
		if ($count > 0) {
			$totalPage = ceil($count / $this->number);
			$page > $totalPage && $page = $totalPage;
			$start = ($page - 1) * $this->number;
			$list = $this->_getDs()->getByUid($this->loginUser->uid, $this->number, $start);
		}
		$this->setCurrentLeft('profile', 'work');
		->with(array('_tab' => 'work'), 'args');
		->with($count, 'count');
		->with($list, 'list');
		->with($page, 'page');
		->with(ceil($count / $this->number), 'page_total');
		$this->setYearAndMonth();
	}
	
	/** 
	 * 添加工作经历
	 */
	public function addAction(Request $request) {
		$workDm = new PwWorkDm();
		$workDm->setCompany($request->get('company'), 'post');
		$workDm->setStartTime($request->get('startYear', 'post'), $request->get('startMonth', 'post'));
		$workDm->setEndTime($request->get('endYear', 'post'), $request->get('endMonth', 'post'));
		$workDm->setUid($this->loginUser->uid);
		
		$workDs = $this->_getDs();
		if (($result = $workDs->addWorkExperience($workDm)) instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		return $this->showMessage('USER:work.add.success');
	}
	
	/**
	 * 删除工作经历
	 */
	public function deleteAction(Request $request) {
		$id = $request->get('id', 'post');
		if (!$id) {
			return $this->showError('operate.fail');
		}

		$workDs = $this->_getDs();
		if (($result = $workDs->deleteWorkExperience($id, $this->loginUser->uid)) instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		return $this->showMessage('USER:work.delete.success');
	}
	
	/**
	 * 编辑工作经历
	 */
	public function editAction(Request $request) {
		$workDm = new PwWorkDm();
		$workDm->setCompany($request->get('company', 'post'));
		$workDm->setStartTime($request->get('startYear', 'post'), $request->get('startMonth', 'post'));
		$workDm->setEndTime($request->get('endYear', 'post'), $request->get('endMonth', 'post'));
		$workDm->setUid($this->loginUser->uid);
		$workDs = $this->_getDs();
		if (($result = $workDs->editWorkExperience($request->get('id', 'post'), $workDm)) instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		return $this->showMessage('USER:work.update.success');
	}
	
	/**
	 * 获得年及月列表
	 */
	private function setYearAndMonth() {
		$tyear = Tool::time2str(Tool::getTime(), 'Y');
		->with(range($tyear, $tyear-100, -1), 'years');
		->with(range(1, 12, 1), 'months');
	}
	
	/** 
	 * 返回用户工作经历
	 *
	 * @return PwWork
	 */
	private function _getDs() {
		return app('SRV:work.PwWork');
	}
}