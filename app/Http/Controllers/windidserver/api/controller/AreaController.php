<?php
Wind::import('APPS:api.controller.OpenBaseController');

/**
 * the last known user to change this file in the repository  <$LastChangedBy: jieyin $>
 * @author $Author: jieyin $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: AreaController.php 24834 2013-02-22 06:43:43Z jieyin $ 
 * @package 
 */
class AreaController extends OpenBaseController{
	
	public function getAction(Request $request) {
		$result = $this->_getAreaDs()->getArea($request->get('id', 'get'));
		$this->output($result);
	}
	
	public function fetchAction(Request $request) {
		$result = $this->_getAreaDs()->fetchByAreaid($request->get('ids', 'get'));
		$this->output($result);
	}
	
	public function getByParentidAction(Request $request) {
		$result = $this->_getAreaDs()->getAreaByParentid($request->get('parentid', 'get'));
		$this->output($result);
	}
	
	public function getAllAction(Request $request) {
		$result = $this->_getAreaDs()->fetchAll();
		$this->output($result);
	}

	public function getAreaInfoAction(Request $request) {
		$areaid = $request->get('areaid', 'get');
		$result = $this->_getAreaService()->getAreaInfo($areaid);
		$this->output($result);
	}

	public function fetchAreaInfoAction(Request $request) {
		$areaids = $request->get('areaids', 'get');
		$result = $this->_getAreaService()->fetchAreaInfo($areaids);
		$this->output($result);
	}

	public function getAreaRoutAction(Request $request) {
		$areaid = $request->get('areaid', 'get');
		$result = $this->_getAreaService()->getAreaRout($areaid);
		$this->output($result);
	}

	public function fetchAreaRoutAction(Request $request) {
		$areaids = $request->get('areaids', 'get');
		$result = $this->_getAreaService()->fetchAreaRout($areaids);
		$this->output($result);
	}

	public function getAreaTreeAction(Request $request) {
		$result = $this->_getAreaService()->getAreaTree();
		$this->output($result);
	}

	public function updateAction(Request $request) {
		$id = $request->get('id', 'get');
		list($name, $parentid, $joinname) = $request->get(array('name', 'parentid', 'joinname'), 'post');

		Wind::import('WSRV:area.dm.WindidAreaDm');
		$dm = new WindidAreaDm();
		$dm->setAreaid($id);
		isset($name) && $dm->setName($name);
		isset($parentid) && $dm->setParentid($parentid);
		isset($joinname) && $dm->setJoinname($joinname);

		$result = $this->_getAreaDs()->updateArea($dm);
		$this->output(WindidUtility::result($result));
	}

	public function batchaddAction(Request $request) {
		$dms = array();
		list($ids, $name, $parentid, $joinname) = $request->get(array('id', 'name', 'parentid', 'joinname'), 'post');
		Wind::import('WSRV:area.dm.WindidAreaDm');
		foreach ($ids as $k => $id) {
			$dm = new WindidAreaDm();
			$dm->setAreaid($id);
			isset($name[$k]) && $dm->setName($name[$k]);
			isset($parentid[$k]) && $dm->setParentid($parentid[$k]);
			isset($joinname[$k]) && $dm->setJoinname($joinname[$k]);
			$dms[] = $dm;
		}
		$result = $this->_getAreaDs()->batchAddArea($dms);
		$this->output(WindidUtility::result($result));
	}

	public function deleteAction(Request $request) {
		$id = $request->get('id', 'post');
		$result = $this->_getAreaDs()->deleteArea($id);
		$this->output(WindidUtility::result($result));
	}
	
	private function _getAreaDs() {
		return app('WSRV:area.WindidArea');
	}

	private function _getAreaService() {
		return app('WSRV:area.srv.WindidAreaService');
	}
}
?>