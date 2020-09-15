<?php
defined('WEKIT_VERSION') || exit('Forbidden');
Wind::import('WIND:utility.WindJson');
/**
 * 错误处理
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PwErrorController.php 1532 2011-12-15上午11:00:42 xiaoxiao $
 * @package library.base
 */
class PwErrorController extends WindErrorHandler {

	protected $state = 'fail';
	
	/*
	 * (non-PHPdoc) @see WindErrorHandler::beforeAction()
	 */
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		->with($this->error['referer'], 'referer');
		->with($this->error['refresh'], 'refresh');
		isset($this->error['state']) && $this->state = $this->error['state'];
		unset($this->error['referer'], $this->error['refresh'], $this->error['state']);
	}

	/**
	 * 错误提示
	 *
	 * @see WindErrorHandler::run()
	 */
	public function run() {
		->with($this->state, 'state');
		if (isset($this->error['data'])) {
			->with($this->error['data'], 'data');
			unset($this->error['data']);
		}
		if (isset($this->error['html'])) {
			->with($this->error['html'], 'html');
			unset($this->error['html']);
		}
		->with($this->error, "message");

		//set layout for common request
		view('TPL:common.error');

		if (!$request->getIsAjaxRequest()) {
			$this->setLayout('TPL:common.layout_error');
			$lang = Wind::getComponent('i18n');
			Core::setGlobal(NEXT_VERSION, 'version');
			Core::setGlobal(array('title' => strtr($lang->getMessage('SEO:' . $this->state . '.page.title'), array('{sitename}' => Core::C('site', 'info.name')))), 'seo');
		}
	}
	
	/*
	 * (non-PHPdoc) @see WindSimpleController::afterAction()
	 */
	public function afterAction($handlerAdapter) {
		parent::afterAction($handlerAdapter);
		
		$debug = Core::C('site', 'debug') || !Core::C('site', 'css.compress');
		Core::setGlobal(array('debug' => $debug ? '/dev' : '/build'), 'theme');
		$this->setTheme('site', null);
		
		/* @var $resource WindLangResource */
		$resource = app(MessageTool::class);
		$_error = $this->getForward()->getVars('message');
		if ($resource !== null) {
			foreach ($_error as $key => $value) {
				if (is_array($value))
					list($value, $var) = $value;
				else
					$var = array();
				$message = $resource->getMessage($value, $var);
				$message && $_error[$key] = $message;
			}
		}
		$this->getForward()->setVars(array('message' => $_error, '__error' => ''));
		
		$type = $request->getAcceptTypes();
		// 如果是含有上传的递交，不能采用ajax的方式递交，需要以html的方式递交，并且返回的结果需要是json格式，将以json=1传递过来标志
		$json = $request->get('_json');
		$requestJson = $request->getIsAjaxRequest() && strpos(strtolower($type), "application/json") !== false;
		if ($requestJson || $json == 1) {
			$this->getResponse()->setHeader('Content-type', 'application/json; charset=' . Core::V('charset'));
			$vars = $this->getForward()->getVars();
			isset($vars['referer']) && $vars['referer'] = rawurlencode($vars['referer']);
			foreach ($vars as $key => $value) {
				if ($key == 'html') continue;
				$vars[$key] = Security::escapeArrayHTML($value);
			}
			echo Tool::jsonEncode($vars);
			exit();
		}
	}

	/**
	 * 风格设置
	 *
	 * 设置当前页面风格，需要两个参数，$type风格类型，$theme该类型下风格
	 * 
	 * @see WindSimpleController::setTheme()
	 * @param string $type 风格类型(site,space,area...)
	 * @param string $theme 风格别名
	 */
	protected function setTheme($type, $theme) {
		$themePack = Core::C('site', 'theme.' . $type . '.pack');
		$themePack = 'THEMES:' . $themePack;
		if (!$theme) $theme = Core::C('site', 'theme.' . $type . '.default');
		parent::setTheme($theme, $themePack);
	}
}