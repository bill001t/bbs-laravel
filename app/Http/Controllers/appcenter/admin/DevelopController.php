<?php
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('APPCENTER:service.srv.PwGenerateApplication');
/**
 * 开发助手
 *
 * @author Shi Long <long.shi@alibaba-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: DevelopController.php 24585 2013-02-01 04:02:37Z jieyin $
 * @package appcenter.admin
 */
class DevelopController extends AdminBaseController {
	public function run() {
		$this->_installService();
	}
	
	public function doRunAction(Request $request) {
		$this->_generate();
	}
	
	/**
	 * 编辑
	 *
	 */
	public function editAction(Request $request) {
		$alias = $request->get('alias', 'get');
		/* @var $app PwApplication */
		$app = app('APPCENTER:service.PwApplication');
		$app = $app->findByAlias($alias);
		->with($app, 'app');
	}
	
	/**
	 * 编辑xml
	 *
	 */
	public function editxmlAction(Request $request) {
		$alias = $request->get('alias', 'get');
		/* @var $app PwApplication */
		$app = app('APPCENTER:service.PwApplication');
		$app = $app->findByAlias($alias);
		->with($app, 'app');
		$manifest = WindFile::read(Wind::getRealPath('EXT:' . $alias . '.Manifest.xml', true));
		->with($manifest, 'manifest');
	}
	
	/**
	 * 编辑我的扩展
	 *
	 */
	public function edithookAction(Request $request) {
		$alias = $request->get('alias', 'get');
		/* @var $app PwApplication */
		$appDs = app('APPCENTER:service.PwApplication');
		$app = $appDs->findByAlias($alias);
		->with($app, 'app');
		
		$myHooks = app('hook.PwHookInject')->findByAppId($alias);
		->with(array('myHooks' => $myHooks));
	}
	
	/**
	 * 显示添加扩展页面
	 *
	 */
	public function addhookAction(Request $request) {
		Wind::import('SRV:hook.dm.PwHookSo');
		$hooks = app('hook.PwHooks')->fetchList(0);
		$hooks = array_reverse($hooks, true);
		->with($request->get('alias'), 'alias');
		->with(array('hooks' => $hooks));
	}
	
	/**
	 * 添加扩展 提交
	 *
	 */
	public function doEditHookAction(Request $request) {
		list($hookname, $alias) = $request->get(array('hook_name', 'alias'));
		/* @var $app PwApplication */
		$appDs = app('APPCENTER:service.PwApplication');
		$appInfo = $appDs->findByAlias($alias);
		$app = new PwGenerateApplication();
		$app->setAlias($alias);
		$app->setName($appInfo['name']);
		$app->setDescription($appInfo['description']);
		$app->setVersion($appInfo['version']);
		$app->setPwversion($appInfo['pwversion']);
		$app->setAuthor($appInfo['author_name']);
		$app->setEmail($appInfo['author_email']);
		$app->setWebsite($appInfo['website']);
		$r = $app->generateHook($hookname);
		if ($r instanceof ErrorBag) {
			return $this->showError($r->getError());
		}
		app('APPCENTER:service.srv.PwDebugApplication')->compile();
		return $this->showMessage('success');
	}
	
	public function doEditAction(Request $request) {
		list($appid, $name, $alias, $description, $version, $pwversion, $author, $email, $website) =
		$request->get(array('appid', 'name', 'alias', 'description', 'version', 'pwversion', 'author', 'email', 'website'), 'post');
		if (!$name || !$alias || !$version || !$pwversion) return $this->showError('APPCENTER:empty');
		if (!preg_match('/^[a-z][a-z0-9]+$/i', $alias)) return $this->showError('APPCENTER:illegal.alias');
		$app = new PwGenerateApplication();
		$app->setAlias($alias);
		$app->setName($name);
		$app->setDescription($description);
		$app->setVersion($version);
		$app->setPwversion($pwversion);
		$app->setAuthor($author);
		$app->setEmail($email);
		$app->setWebsite($website);
		$r = $app->generateBaseInfo();
		if ($r instanceof ErrorBag) return $this->showError($r->getError());
		app('APPCENTER:service.srv.PwDebugApplication')->compile();
		return $this->showMessage('success', 'appcenter/develop/edit?alias=' . $alias);
	}
	
	public function doEditXmlAction(Request $request) {
		list($xml, $alias) = $request->get(array('xml', 'alias'), 'post');
		$file = Wind::getRealDir('EXT:' . $alias) . '/Manifest.xml';
		Wind::import('WIND:parser.WindXmlParser');
		$parser = new WindXmlParser();
		if (!$parser->parseXmlStream($xml)) return $this->showError('APPCENTER:xml.fail');
		$r = WindFile::write($file, $xml);
		if (!$r) {
			return $this->showError('APPCENTER:generate.copy.fail');
		}
		app('APPCENTER:service.srv.PwDebugApplication')->compile(true);
		return $this->showMessage('success');
	}
	
	private function _generate() {
		list($name, $alias, $description, $version, $pwversion, $service, $need_admin, $need_service, $website) =
		$request->get(array('name', 'alias', 'description', 'version', 'pwversion', 'service', 'need_admin', 'need_service', 'website'), 'post');
		if (!$name || !$alias || !$version || !$pwversion) return $this->showError('APPCENTER:empty');
		if (!preg_match('/^[a-z][a-z0-9]+$/i', $alias)) return $this->showError('APPCENTER:illegal.alias');
		list($author, $email) = $request->get(array('author', 'email'), 'post');
		$app = new PwGenerateApplication();
		$app->setAlias($alias);
		$app->setName($name);
		$app->setDescription($description);
		$app->setVersion($version);
		$app->setPwversion($pwversion);
		$app->setAuthor($author);
		$app->setEmail($email);
		$app->setWebsite($website);
		$app->setInstallation_service($service ? implode(',', $service) : '');
		$app->setNeed_admin($need_admin);
		$app->setNeed_service($need_service);
		$r = $app->generate();
		if ($r instanceof ErrorBag) return $this->showError($r->getError());
		app('APPCENTER:service.srv.PwDebugApplication')->installPack(EXT_PATH . $alias);
		return $this->showMessage(array('APPCENTER:develop.success', array($name, $alias)), 'appcenter/app/run');
	}
	
	private function _installService($exists = array()) {
		$install = app('APPCENTER:service.srv.PwInstallApplication');
		$temp = $install->getConfig('installation-service');
		$service = array();
		$lang = Wind::getComponent('i18n');
		foreach ($temp as $k => $v) {
			$service[$k] = $lang->getMessage($v['message']);
		}
		->with($service, 'service');
		$keys = array_keys($service);
		$temp = array();
		foreach ($exists as $s) {
			if (isset($s['_key']) && in_array($s['_key'], $keys)) $temp[] = $s['_key'];
		}
		->with($temp, 'exists');
	}
}

?>