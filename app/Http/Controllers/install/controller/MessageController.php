<?php
/**
 * 后台管理平台错误操作处理
 *
 * @author Qiong Wu <papa0924@gmail.com> 2011-10-14
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: MessageController.php 3340 2011-12-19 14:32:34Z yishuo $
 * @package common
 */
class MessageController extends PwErrorController {
	/**
	 * 消息提示
	 * 
	 * @see WindErrorHandler::run()
	 */
	public function run() {
		->with($this->state, 'state');
		if (isset($this->error['data'])) {
			->with($this->error['data'], "data");
			unset($this->error['data']);
		}
		->with($this->error, "message");
		return view('common.error');
	}
	
	/**
	 * 重写下afterAction，不去i18n解析
	 * 
	 */
	public function afterAction($handlerAdapter) {
		$_error = $this->getForward()->getVars('message');
		$this->getForward()->setVars(array('message' => $_error, '__error' => ''));
		$type = $request->getAcceptTypes();
		if ($request->getIsAjaxRequest() && strpos(strtolower($type), "application/json") !== false) {
			$this->getResponse()->setHeader('Content-type', 'application/json; charset=' . Core::V('charset'));
			exit(Tool::jsonEncode($this->getForward()->getVars()));
		}
	}
}
