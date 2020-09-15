<?php
Wind::import('ADMIN:library.AdminBaseController');

/**
 * 后台设置-验证机制配置
 *
 * @author Qiong Wu <papa0924@gmail.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: VerifyController.php 28863 2013-05-28 03:22:39Z jieyin $
 * @package 
 */
class VerifyController extends AdminBaseController {
	
	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		Wind::import('SRV:verify.srv.PwVerifyService');
		$srv =  new PwVerifyService('PwVerifyService_getVerifyType');
		$verifyType = $srv->getVerifyType();

		$config = Core::C()->getValues('verify');
		->with($config, 'config');
		->with($verifyType, 'verifyType');
	}

	/**
	 * 配置增加表单处理器
	 *
	 * @return void
	 */
	public function dorunAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');
		$questions = $request->get('contentQuestions', 'post');
		$_questions = array();
		!$questions && $questions = array();
		foreach ($questions as $key => $value) {
			if (empty($value['ask']) && empty($value['answer'])) continue;
			if ($value['ask'] && empty($value['answer'])) return $this->showError('ADMIN:verify.answer.empty');
			$_questions[] = $value;
		
		}
		$type = $request->get('type', 'post');
		if ($type == 'flash') {
			if (!class_exists('SWFBitmap')) return $this->showError('ADMIN:verify.flash.not.allow');
		}
		$config = new PwConfigSet('verify');
		$config->set('type', $request->get('type', 'post'))
			->set('randtype', $request->get('randtype', 'post'))
			->set('content.type', $request->get('contentType', 'post'))
			->set('content.length', $request->get('contentLength', 'post'))
			->set('content.questions', $_questions)
			->set('width', 240)
			->set('height', 60)
			->set('content.showanswer', $request->get('contentShowanswer', 'post'))
			->set('voice', $request->get('voice', 'post'))
			->flush();
		return $this->showMessage('ADMIN:success');
	}
	
	/**
	 * 站点设置
	 *
	 * @return void
	 */
	public function setAction(Request $request) {
		$config = Core::C()->getValues('verify');
		->with($config, 'config');

		//扩展：key => title
		$verifyExt = array();
		$verifyExt = SimpleHook::getInstance('verify_showverify')->runWithFilters($verifyExt);
		->with($verifyExt, 'verifyExt');
	}
			
	/**
	 * 全局配置增加表单处理器
	 *
	 * @return void
	 */
	public function dosetAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');
		$ext = $request->get('ext', 'post');
		$extConfig = array();
		foreach ($ext as $key => $value) {
			if ($value == 1) {
				$extConfig[] = $key;
			}
		}
		$config = new PwConfigSet('verify');
		$config->set('showverify', $extConfig)->flush();	
		return $this->showMessage('ADMIN:success');
	
	}
}