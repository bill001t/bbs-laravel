<?php
Wind::import('ADMIN:library.AdminBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: liusanbian $>
 * @author $Author: liusanbian $ Foxsee@aliyun.com
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: ModelController.php 12232 2012-06-19 17:37:18Z liusanbian $ 
 * @package 
 */
class ModelController extends AdminBaseController {
	
	public function run() {
		
		->with($this->_getDesignModelDs()->getModelList(),'list');
	}
	
	public function addAction(Request $request) {
		
		->with($this->_getDesignService()->getDesignModelType(), 'types');
	}
	
	public function doaddAction(Request $request){
		$resource = $this->_getDesignModelDs()->addModel($request->get('flag','post'), $request->get('name','post'), $request->get('type','post'), $request->get('signkeys','post'));
		if (!$resource ) return $this->showError("operate.fail");
		return $this->showMessage("operate.success");
	}
	
	public function editAction(Request $request) {
		$flag = $request->get('flag','get');
		if (!$flag) return $this->showError("operate.fail");
		->with($this->_getDesignModelDs()->getModel($flag), 'info');
		->with($this->_getDesignService()->getDesignModelType(), 'types');
	}
	
	public function doeditAction(Request $request){
		$flag = $request->get('flag','post');
		if (!$flag) return $this->showError("operate.fail");
		$resource = $this->_getDesignModelDs()->updateModel($flag, $request->get('name','post'), $request->get('type','post'), $request->get('signkeys','post'));
		if (!$resource ) return $this->showError("operate.fail");
		return $this->showMessage("operate.success");
	}
	
	/**
	 * 
	 * getDesignService
	 *
	 * @return PwDesignService
	 */
	private function _getDesignService() {
		return app('design.srv.PwDesignService');
	}
	
	/**
	 * 
	 * getDesignModelDs
	 *
	 * @return PwDesignModel
	 */
	private function _getDesignModelDs() {
		return app('design.PwDesignModel');
	}
}