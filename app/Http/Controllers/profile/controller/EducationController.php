<?php
Wind::import('APPS:.profile.controller.BaseProfileController');
Wind::import('SRV:education.srv.helper.PwEducationHelper');
Wind::import('SRV:education.dm.PwEducationDm');
/**
 * 教育经历
 *
 * @author xiaoxia.xu <x_824@sina.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: EducationController.php 28848 2013-05-28 02:21:12Z jieyin $
 * @package src.productions.u.controller.profile
 */
class EducationController extends BaseProfileController {
	protected $number = 10;

	/* (non-PHPdoc)
	 * @see BaseExtendsInjector::run()
	 */
	public function run() {
		$list = $this->_getService()->getEducationByUid($this->loginUser->uid, 100, true);
		->with($list, 'list');
		->with(PwEducationHelper::getDegrees(), 'degrees');
		->with(PwEducationHelper::getEducationYear(), 'years');
		$this->setCurrentLeft();
		->with(array('_tab' => 'education'), 'args');
	}
	
	/** 
	 * 添加教育经历
	 */
	public function addAction(Request $request) {
		$educationDm = new PwEducationDm();
		$educationDm->setSchoolid($request->get('schoolid', 'post'));
		$educationDm->setStartTime($request->get('startYear', 'post'));
		$educationDm->setDegree($request->get('degree', 'post'));
		$educationDm->setUid($this->loginUser->uid);
		$educationDs = $this->_getDs();
		if (($result = $educationDs->addEducation($educationDm)) instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		return $this->showMessage('USER:education.add.success');
	}
	
	/**
	 * 删除教育经历
	 */
	public function deleteAction(Request $request) {
		$id = $request->get('id', 'post');
		if (!$id) {
			return $this->showError('operate.fail');
		}

		$educationDs = $this->_getDs();
		if (($result = $educationDs->deleteEducation($id, $this->loginUser->uid)) instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		return $this->showMessage('USER:education.delete.success');
	}
	
	/**
	 * 编辑教育经历
	 */
	public function editAction(Request $request) {
		$educationDm = new PwEducationDm();
		$educationDm->setSchoolid($request->get('schoolid', 'post'));
		$educationDm->setStartTime($request->get('startYear', 'post'));
		$educationDm->setDegree($request->get('degree', 'post'));
		$educationDm->setUid($this->loginUser->uid);
		$educationDs = $this->_getDs();
		if (($result = $educationDs->editEducation($request->get('id'), $educationDm)) instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		return $this->showMessage('USER:education.update.success');
	}
	
	/** 
	 * 返回用户教育经历
	 *
	 * @return PwEducation
	 */
	private function _getDs() {
		return app('SRV:education.PwEducation');
	}
	
	/** 
	 * 返回用户教育经历Service
	 *
	 * @return PwEducationService
	 */
	private function _getService() {
		return app('SRV:education.srv.PwEducationService');
	}
}
