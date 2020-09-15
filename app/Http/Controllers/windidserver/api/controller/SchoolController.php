<?php
Wind::import('APPS:api.controller.OpenBaseController');

/**
 * the last known user to change this file in the repository  <$LastChangedBy: jieyin $>
 * @author $Author: jieyin $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: SchoolController.php 24834 2013-02-22 06:43:43Z jieyin $ 
 * @package 
 */
class SchoolController extends OpenBaseController {
	
	public function getAction(Request $request) {
		$result = $this->_getSchoolDs()->getSchool($request->get('id', 'get'));
		$this->output($result);
	}
	
	public function fetchAction(Request $request){
		$result = $this->_getSchoolDs()->fetchSchool($request->get('ids', 'get'));
		$this->output($result);
	}
	
	public function getSchoolByAreaidAndTypeidAction(Request $request) {
		$result = $this->_getSchoolDs()->getSchoolByAreaidAndTypeid($request->get('areaid', 'get'), $request->get('typeid', 'get'));
		$this->output($result);
	}
	
	public function searchAction(Request $request) {
		$start = (int)$request->get('start', 'get');
		$limit = (int)$request->get('limit', 'get');
		$name = $request->get('name', 'get');
		$typeid = $request->get('typeid', 'get');
		$areaid = $request->get('areaid', 'get');
		$firstchar = $request->get('first_char', 'get');
		!$limit && $limit = 10;
		!$start && $start = 0;
		Wind::import('WINDID:service.school.vo.WindidSchoolSo');
		$schoolSo = new WindidSchoolSo();
		$schoolSo->setName($name)
			->setTypeid($typeid)
			->setFirstChar($firstchar)
			->setAreaid($areaid);
		$result = $this->_getSchoolDs()->searchSchool($schoolSo, $limit, $start);
		$this->output($result);
	}

	public function searchDataAction(Request $request) {
		$start = (int)$request->get('start', 'get');
		$limit = (int)$request->get('limit', 'get');
		$name = $request->get('name', 'get');
		$typeid = $request->get('typeid', 'get');
		$areaid = $request->get('areaid', 'get');
		$firstchar = $request->get('first_char', 'get');
		!$limit && $limit = 10;
		!$start && $start = 0;
		Wind::import('WINDID:service.school.vo.WindidSchoolSo');
		$schoolSo = new WindidSchoolSo();
		$schoolSo->setName($name)
			->setTypeid($typeid)
			->setFirstChar($firstchar)
			->setAreaid($areaid);
		$result = $this->_getSchoolService()->searchSchool($schoolSo, $limit, $start);
		$this->output($result);
	}
	
	public function addAction(Request $request) {
		list($name, $firstchar, $typeid, $areaid) = $request->get(array('name', 'first_char', 'typeid', 'areaid'), 'post');
		Wind::import('WSRV:school.dm.WindidSchoolDm');
		$dm = new WindidSchoolDm();
		isset($name) && $dm->setName($name);
		isset($firstchar) && $dm->setFirstChar($firstchar);
		isset($typeid) && $dm->setTypeid($typeid);
		isset($areaid) && $dm->setAreaid($areaid);
		$result = $this->_getSchoolDs()->addSchool($dm);
		$this->output(WindidUtility::result($result));
	}
	
	public function batchAddAction(Request $request) {
		list($name, $firstchar, $typeid, $areaid) = $request->get(array('name', 'first_char', 'typeid', 'areaid'), 'post');
		Wind::import('WSRV:school.dm.WindidSchoolDm');
		foreach ($name AS $k=>$v) {
			$dm = new WindidSchoolDm();
			isset($name[$k]) && $dm->setName($name[$k]);
			isset($firstchar[$k]) && $dm->setFirstChar($firstchar[$k]);
			isset($typeid[$k]) && $dm->setTypeid($typeid[$k]);
			isset($areaid[$k]) && $dm->setAreaid($areaid[$k]);
			$dms[] = $dm;
		}
		$result = $this->_getSchoolDs()->batchAddSchool($dms);
		$this->output(WindidUtility::result($result));
	}

	public function updateAction(Request $request) {
		$ids = $request->get('id', 'get');
		list($name, $firstchar, $typeid, $areaid) = $request->get(array('name', 'first_char', 'typeid', 'areaid'), 'post');
		Wind::import('WSRV:school.dm.WindidSchoolDm');
		foreach ($name AS $k=>$id) {
			$dm = new WindidSchoolDm();
			$dm->setSchoolid($id);
			isset($name[$k]) && $dm->setName($name[$k]);
			isset($firstchar[$k]) && $dm->setFirstChar($firstchar[$k]);
			isset($typeid[$k]) && $dm->setTypeid($typeid[$k]);
			isset($areaid[$k]) && $dm->setAreaid($areaid[$k]);
			$dms[] = $dm;
		}
		$result = $this->_getSchoolDs()->batchAddSchool($dms);
		$this->output(WindidUtility::result($result));
	}

	public function deleteAction(Request $request) {
		$schoolid = (int)$request->get('id', 'post');
		$result = $this->_getSchoolDs()->deleteSchool($schoolid);
		$this->output(WindidUtility::result($result));
	}
	
	private function _getSchoolDs() {
		return app('WSRV:school.WindidSchool');
	}

	private function _getSchoolService() {
		return app('WSRV:school.srv.WindidSchoolService');
	}
}
?>