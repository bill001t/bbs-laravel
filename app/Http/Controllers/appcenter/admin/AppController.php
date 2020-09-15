<?php
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('APPCENTER:service.srv.helper.PwApplicationHelper');
Wind::import('APPCENTER:service.srv.helper.PwManifest');
/**
 * 后台 - 我的应用
 *
 * @author Zhu Dong <zhudong0808@gmail.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: AppController.php 28922 2013-05-30 08:02:34Z long.shi $
 * @package appcenter.admin
 */
class AppController extends AdminBaseController {
	private $perpage = 10;

	/**
	 * 应用已安装
	 *
	 * @see WindController::run()
	 */
	public function run() {
		$page = (int) $request->get('page');
		$page < 1 && $page = 1;
		
		$count = (int) $this->_appDs()->count();
		$_page = ceil($count / $this->perpage);
		$page > $_page && $page = $_page;
		list($start, $num) = Tool::page2limit($page, $this->perpage);
		$apps = $this->_appDs()->fetchByPage($num, $start);
		->with(
			array('perpage' => $this->perpage, 'page' => $page, 'count' => $count, 'apps' => $apps));
	}

	/**
	 * 获取应用更新信息及卸载信息
	 */
	public function refreshAction(Request $request) {
		$app_ids = $request->get('app_ids');
		$apps = $data = array();
		$url = PwApplicationHelper::acloudUrl(
			array('a' => 'forward', 'do' => 'fetchApp', 'appids' => $app_ids));
		$result = PwApplicationHelper::requestAcloudData($url);
		$result['code'] === '0' && $apps = $result['info'];
		foreach (explode(',', $app_ids) as $v) {
			$data[$v] = array(
				'update_url' => '', 
				'admin_url' => trim($apps[$v]['admin_url'], '\'"'), 
				'update_url' => $apps[$v]['update'] ? 1 : 0, 
				'open_new' => $apps[$v]['open_new'] ? 1 : 0);
		}
		->with($data, 'data');
		return $this->showMessage('success');
	}

	/**
	 * 本地安装 - 上传
	 */
	public function uploadAction(Request $request) {
		$authkey = 'AdminUser';
		$pre = Core::C('site', 'cookie.pre');
		$pre && $authkey = $pre . '_' . $authkey;
		$winduser = $request->get($authkey, 'post');
		if (!$winduser) return $this->showError('login.not');
		list($type, $u, $pwd) = explode("\t", Tool::decrypt(urldecode($winduser)));
		if ($type == 'founder') {
			$founders = app('ADMIN:service.srv.AdminFounderService')->getFounders();
			if (!isset($founders[$u])) return $this->showError('login.not');
			list($md5pwd, $salt) = explode('|', $founders[$u], 2);
			if (Tool::getPwdCode($md5pwd) != $pwd) return $this->showError('login.not');
		} else {
			$r = app('user.PwUser')->getUserByUid($u);
			if (!$r) return $this->showError('login.not');
			if (Tool::getPwdCode($r['password']) != $pwd) return $this->showError('login.not');
		}
		
		Wind::import('SRC:applications.appcenter.service.srv.helper.PwApplicationUpload');
		$upload = new PwApplicationUpload();
		$upload->dir = Wind::getRealDir($this->_installService()->getConfig('tmp_dir'), true) . '/';
		$uploaddb = $upload->execute();
		if ($uploaddb instanceof ErrorBag) return $this->showError($uploaddb->getError());
		if (empty($uploaddb)) return $this->showError('upload.fail');
		->with(
			array('filename' => $uploaddb[0]['name'], 'file' => $uploaddb[0]['fileuploadurl']), 
			'data');
		return $this->showMessage('success');
	}

	/**
	 * 本地安装, 打印本地安装页面
	 */
	public function installAction(Request $request) {
		$ext = Wind::getRealDir('EXT:', true);
		$dirs = WindFolder::read($ext, WindFolder::READ_DIR);
		$manifests = array();
		$result = array_keys($this->_appDs()->fetchByAlias($dirs, 'alias'));
		$temp = array_diff($dirs, $result);
		$to_install = array();
		foreach ($temp as $v) {
			if (file_exists($ext . $v . '/Manifest.xml')) $to_install[] = $v;
		}
		->with($to_install, 'apps');
	}
	
	/**
	 * 目录扫描安装
	 *
	 */
	public function toInstallAction(Request $request) {
		$apps = $request->get('apps', 'get');
		$ext = Wind::getRealDir('EXT:', true);
		$srv = app('APPCENTER:service.srv.PwDebugApplication');
		foreach ($apps as $v) {
			$r = $srv->installPack($ext . $v);
			if ($r instanceof ErrorBag) return $this->showError($r->getError());
		}
		return $this->showMessage('success', 'appcenter/app/install', true);
	}

	/**
	 * 本地安装, 分步模式执行应用安装
	 */
	public function doInstallAction(Request $request) {
		list($file, $step, $hash) = $request->get(array('file', 'step', 'hash'));
		$install = $this->_installService();
		if ($file) {
			$file = Wind::getRealDir($install->getConfig('tmp_dir'), true) . '/' . $file;
			$install->setTmpPath(dirname($file));
			if (!WindFile::isFile($file)) return $this->showError('APPCENTER:install.checkpackage.fail');
			$_r = $install->extractPackage($file);
			if (true !== $_r) return $this->showError('APPCENTER:install.checkpackage.format.fail');
			$this->addMessage('APPCENTER:install.step.express');
			$_r = $install->initInstall();
			if (true !== $_r) return $this->showError('APPCENTER:install.initinstall.fail');
			$this->addMessage('APPCENTER:install.step.init');
			$hash = $install->getHash();
			$this->addMessage('APPCENTER:install.step.install');
		}
		
		$step || $step = 0;
		//$_r = $install->doInstall($step, $hash);
		//在360和ie下，写日志有问题，而且多web环境下，多进程安装也是有问题的
		$_r = $install->doInstall('all', $hash);
		if (true === $_r) {
			$install->clear();
			return $this->showMessage('APPCENTER:install.success');
		} elseif (is_array($_r)) {
			->with(array('step' => $_r[0], 'hash' => $hash), 'data');
			return $this->showMessage($_r[1]);
		} else {
			$install->rollback();
			$this->addMessage(array('step' => $step, 'hash' => $hash), 'data');
			return $this->showError($_r->getError());
		}
	}

	/**
	 * 测试升级流程
	 */
	public function testUpgradeAction(Request $request) {
		list($file) = $request->get(array('file'));
		/* @var $install PwUpgradeApplication */
		$install = app('APPCENTER:service.srv.PwUpgradeApplication');
		$install->_appId = 'L0001344318635mEhO';
		$file = Wind::getRealDir($install->getConfig('tmp_dir'), true) . '/' . $file;
		$install->setTmpPath(dirname($file));
		$install->extractPackage($file);
		$install->initInstall();
		$_r = $install->doUpgrade();
		if (true === $_r) {
			$install->clear();
			return $this->showMessage('APPCENTER:install.success');
		} else {
			$install->rollback();
			return $this->showError($_r->getError());
		}
	}

	/**
	 * 删除已上传压缩包
	 */
	public function delFileAction(Request $request) {
		$file = $request->get('file', 'post');
		if ($file && file_exists(ATTACH_PATH . $file)) {
			WindFile::del(ATTACH_PATH . $file);
		}
		return $this->showMessage('success');
	}
	
	/**
	 * 删除应用目录
	 *
	 */
	public function delFolderAction(Request $request) {
		$folder = $request->get('folder', 'post');
		if ($folder) {
			is_dir(EXT_PATH . $folder) && WindFolder::clearRecur(EXT_PATH . $folder, true);
			is_dir(THEMES_PATH . 'extres/' . $folder) && WindFolder::clearRecur(THEMES_PATH . 'extres/' . $folder, true);
		}
		return $this->showMessage('success');
	}

	/**
	 * 应用搜索
	 */
	public function searchAction(Request $request) {
		$keyword = $request->get('keyword', 'post');
		$apps = array();
		$count = $this->_appDs()->countSearchByName($keyword);
		if ($count > 0) {
			$page = intval($request->get('page'));
			$total = ceil($count / $this->perpage);
			$page < 1 && $page = 1;
			$page > $total && $page = $total;
			list($start, $num) = Tool::page2limit($page, $this->perpage);
			$apps = $this->_appDs()->searchByName($keyword, $num, $start);
		}
		->with(
			array(
				'perpage' => $this->perpage, 
				'page' => $page, 
				'count' => $count, 
				'apps' => $apps, 
				'keyword' => $keyword, 
				'search' => 1));
		return view('app_run');
	}

	/**
	 * 获取扩展信息
	 */
	public function hookAction(Request $request) {
		$alias = $request->get('alias');
		$manifest = Wind::getRealPath('EXT:' . $alias . '.Manifest.xml', true);
		$hooks = $injectors = array();
		if (is_file($manifest)) {
			$man = new PwManifest($manifest);
			$hooks = $man->getHooks();
			$injectors = $man->getInjectServices();
		}
		->with(array('hooks' => $hooks, 'injectors' => $injectors));
	}

	/**
	 * 卸载
	 */
	public function uninstallAction(Request $request) {
		$id = $request->get('app_id');
		/* @var $uninstall PwUninstallApplication */
		if ($id[0] !== 'L') {
			$url = PwApplicationHelper::acloudUrl(
				array('a' => 'forward', 'do' => 'uninstallApp', 'appid' => $id));
			$info = PwApplicationHelper::requestAcloudData($url);
			if ($info['code'] !== '0')
				return $this->showError($info['msg']);
			else
				return $this->showMessage('success');
		} else {
			$uninstall = app('APPCENTER:service.srv.PwUninstallApplication');
			$r = $uninstall->uninstall($id);
			if ($r === true) return $this->showMessage('success');
			return $this->showError($r->getError());
		}
	}
	
	public function scanAction(Request $request) {
		$ext = Wind::getRealDir('EXT:', true);
		$dirs = WindFolder::read($ext, WindFolder::READ_DIR);
		$alias = array();
		foreach ($dirs as $file) {
			if (WindFile::isFile($ext . '/' . $file . '/Manifest.xml')) $alias[] = $file;
		}
		$result = $this->_appDs()->fetchByAlias($alias, 'alias');
		$to_install = array_diff($alias, array_keys($result));
		if (!$to_install) return $this->showMessage('success');
		
	}
	
	/**
	 * 升级
	 */
	public function upgradeAction(Request $request) {
		$id = $request->get('app_id');
		$url = PwApplicationHelper::acloudUrl(
			array('a' => 'forward', 'do' => 'upgradeApplication', 'appid' => $id));
		$info = PwApplicationHelper::requestAcloudData($url);
		if ($info['code'] !== '0')
			return $this->showError(array('APPCENTER:update.fail', array($info['msg'])));
		else
			return $this->showMessage('success');
	}
	
	/**
	 * 导出压缩包
	 *
	 */
	public function exportAction(Request $request) {
		$alias = $request->get('alias', 'get');
		Wind::import('LIB:utility.PwZip');
		$dir = Wind::getRealDir('EXT:' . $alias);
		if (!is_dir($dir)) return $this->showError('fail');
		$target = Wind::getRealPath('DATA:tmp.' . $alias . '.zip', true);
		PwApplicationHelper::zip($dir, $target);
		$timestamp = Tool::getTime();
		$this->getResponse()->setHeader('Last-Modified', gmdate('D, d M Y H:i:s', $timestamp + 86400) . ' GMT');
		$this->getResponse()->setHeader('Expires', gmdate('D, d M Y H:i:s', $timestamp + 86400) . ' GMT');
		$this->getResponse()->setHeader('Cache-control', 'max-age=86400');
		$this->getResponse()->setHeader('Content-type', 'application/x-zip-compressed');
		$this->getResponse()->setHeader('Content-Disposition', 'attachment; filename=' . $alias . '.zip');
		$this->getResponse()->sendHeaders();
		@readfile($target);
		WindFile::del($target);
		$this->getResponse()->sendBody();
		exit();
	}

	/**
	 *
	 * @return PwApplication
	 */
	private function _appDs() {
		return app('APPCENTER:service.PwApplication');
	}

	/**
	 *
	 * @return PwInstallApplication
	 */
	private function _installService() {
		return app('APPCENTER:service.srv.PwInstallApplication');
	}
	
}

?>