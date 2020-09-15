<?php
Wind::import('ADMIN:library.AdminBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: jieyin $>
 * @author $Author: jieyin $ Foxsee@aliyun.com
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: ComponentController.php 28810 2013-05-24 08:50:05Z jieyin $
 * @package
 */

class ComponentController extends AdminBaseController {
	
	public function run() {
		$page = (int)$request->get('page','get');
		$flag = $request->get('flag');
		$compid = (int)$request->get('compid');
		$compname = $request->get('compname');
		$perpage = 10;
		$args = array();
		$page =  $page > 1 ? $page : 1;
		list($start, $perpage) = Tool::page2limit($page, $perpage);
		Wind::import('SRV:design.srv.vo.PwDesignComponentSo');
		$vo = new PwDesignComponentSo();
		if ($flag) {
			$vo->setModelFlag($flag);
			$args['flag'] = $flag;
		}
		if ($compid > 0) {
			$vo->setCompid($compid);
			$args['compid'] = $compid;
		}
		if ($compname) {
			$vo->setCompname($compname);
			$args['compname'] = $compname;
		}
		
		$list = $this->_getDesignComponentDs()->searchComponent($vo, $start, $perpage);
		$count = $this->_getDesignComponentDs()->countComponent($vo);
		$models = $this->_getDesignService()->getModelList();
		->with($args, 'args');
		->with($flag, 'flag');
		->with($list, 'list');
		->with($models, 'models');
		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
	}
	
	public function add1Action(Request $request) {
		->with($this->_getDesignService()->getModelList(), 'models');
	}
	
	public function add2Action(Request $request) {
		$flag = $request->get('flag','post');
		if (!$flag) return redirect('design/component/add1'));
		Wind::import('SRV:design.bo.PwDesignModelBo');
		$bo = new PwDesignModelBo($flag);
		->with($flag, 'flag');
		->with($bo->getSignKeys(), 'signKeys');
	}
	
	public function doadd2Action(Request $request) {
		$flag = $request->get('flag','post');
		$name = $request->get('name','post');
		$tpl = $request->get('tpl','post');
		$tpl = $this->_getDesignService()->filterTemplate($tpl);
		if (!$this->_getDesignService()->checkTemplate($tpl)) return $this->showError("DESIGN:template.error");
		$resource = $this->_getDesignComponentDs()->addComponent($flag, $name, $tpl);
		if (!$resource) return $this->showMessage("operate.fail");
		return $this->showMessage("operate.success","design/component/run", true);
	}
	
	public function editAction(Request $request) {
		$id = (int)$request->get('id','get');
		$page = (int)$request->get('page','get');
		$comp = $this->_getDesignComponentDs()->getComponent($id);
		if (!$comp) return $this->showMessage("operate.fail");
		Wind::import('SRV:design.bo.PwDesignModelBo');
		$bo = new PwDesignModelBo($comp['model_flag']);
		->with($bo->getSignKeys(), 'signKeys');
		->with($comp, 'comp');
		->with($page, 'page');
	}
	
	public function doeditAction(Request $request) {
		$page = (int)$request->get('page','post');
		$id = (int)$request->get('compid','post');
		$flag = $request->get('flag','post');
		$name = $request->get('name','post');
		$tpl = $request->get('tpl','post');
		$tpl = $this->_getDesignService()->filterTemplate($tpl);
		if (!$this->_getDesignService()->checkTemplate($tpl)) return $this->showError("DESIGN:template.error");
		if ($id<1) return $this->showError('operate.fail');
		$resource = $this->_getDesignComponentDs()->updateComponent($id, $flag, $name, $tpl);
		if (!$resource) return $this->showMessage('operate.fail');
		return $this->showMessage('operate.success','design/component/run?page='.$page, true);
	}
	
	public function delAction(Request $request) {
		$id = (int)$request->get('id','post');
		if (!$id) return $this->showMessage("operate.fail");
		$resource = $this->_getDesignComponentDs()->deleteComponent($id);
		if (!$resource) return $this->showMessage("operate.fail");
		return $this->showMessage("operate.success");
	}
	
	private function _getDesignService() {
		return app('design.srv.PwDesignService');
	}
	
	private function _getDesignComponentDs() {
		return app('design.PwDesignComponent');
	}
}