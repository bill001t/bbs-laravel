<?php
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('APPCENTER:service.srv.helper.PwApplicationHelper');
/**
 * 后台 - 我的模板
 *
 * @author Zhu Dong <zhudong0808@gmail.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: StyleController.php 24598 2013-02-01 06:44:48Z long.shi $
 * @package appcenter.admin
 */
class StyleController extends AdminBaseController {
	private $perpage = 10;

	/**
	 * 整站模板
	 *
	 * @see WindController::run()
	 */
	public function run() {
		$type = $request->get('type');
		$addons = app('APPCENTER:service.srv.PwInstallApplication')->getConfig(
			'style-type');
		$type || $type = key($addons);
		
		$count = $this->_styleDs()->countByType($type);
		$results = array();
		if ($count > 0) {
			$page = (int) $request->get('page');
			$page < 1 && $page = 1;
			list($start, $num) = Tool::page2limit($page, $this->perpage);
			$results = $this->_styleDs()->getStyleListByType($type, $num, $start);
		}
		->with(
			array(
				'type' => $type, 
				'addons' => $addons, 
				'perpage' => $this->perpage, 
				'page' => $page, 
				'count' => $count, 
				'styles' => $results));
	}

	/**
	 * 界面管理
	 */
	public function manageAction(Request $request) {
		$addons = app('APPCENTER:service.srv.PwInstallApplication')->getConfig(
			'style-type');
		->with($addons, 'addons');
		
		$conf = Core::C('css');
		->with(
			array(
				'logo' => $conf['logo'], 
				'bg' => $conf['bg'], 
				'bgcolor' => $conf['bgcolor'], 
				'bgtile' => $conf['bgtile'], 
				'bgalign' => $conf['bgalign'], 
				'size' => $conf['size'], 
				'font' => $conf['font'], 
				'corelink' => $conf['corelink'], 
				'coretext' => $conf['coretext'], 
				'subjectsize' => $conf['subjectsize'], 
				'contentsize' => $conf['contentsize'],
				'headbg' => $conf['headbg'], 
				'headbgcolor' => $conf['headbgcolor'], 
				'headbgtile' => $conf['headbgtile'], 
				'headbgalign' => $conf['headbgalign'], 
				'headlink' => $conf['headlink'], 
				'headactivelink' => $conf['headactivelink'], 
				'headactivecolor' => $conf['headactivecolor'], 
				'boxbg' => $conf['boxbg'], 
				'boxbgcolor' => $conf['boxbgcolor'], 
				'boxbgtile' => $conf['boxbgtile'], 
				'boxbgalign' => $conf['boxbgalign'], 
				'boxborder' => $conf['boxborder'], 
				'boxlink' => $conf['boxlink'], 
				'boxtext' => $conf['boxtext'], 
				'boxhdbg' => $conf['boxhdbg'], 
				'boxhdbgcolor' => $conf['boxhdbgcolor'], 
				'boxhdbgtile' => $conf['boxhdbgtile'], 
				'boxhdbgalign' => $conf['boxhdbgalign'], 
				'boxhdborder' => $conf['boxhdborder'], 
				'boxhdlink' => $conf['boxhdlink'], 
				'boxhdtext' => $conf['boxhdtext']));
	}

	/**
	 * 界面管理
	 */
	public function doManageAction(Request $request) {
		$config = Core::C('css');
		$logo = $this->_upload('logo');
		if ($logo) {
			$config['logo'] = $logo['path'];
			$old = $request->get('oldlogo');
			$old && Tool::deleteAttach($old);
		}
		$bg = $this->_upload('bg');
		if ($bg) {
			$config['bg'] = $bg['path'];
			$old = $request->get('oldbg');
			$old && Tool::deleteAttach($old);
		}
		$headbg = $this->_upload('headbg');
		if ($headbg) {
			$config['headbg'] = $headbg['path'];
			$old = $request->get('oldheadbg');
			$old && Tool::deleteAttach($old);
		}
		$boxbg = $this->_upload('boxbg');
		if ($boxbg) {
			$config['boxbg'] = $boxbg['path'];
			$old = $request->get('oldboxbg');
			$old && Tool::deleteAttach($old);
		}
		$boxhdbg = $this->_upload('boxhdbg');
		if ($boxhdbg) {
			$config['boxhdbg'] = $boxhdbg['path'];
			$old = $request->get('oldboxhdbg');
			$old && Tool::deleteAttach($old);
		}
		list($color, $headbgcolor, $headlink, $headactivelink, $headactivecolor, $corelink, $coretext, $boxbgcolor, $boxborder, $boxlink, $boxtext, $boxhdbgcolor, $boxhdborder, $boxhdlink, $boxhdtext) = $request->get(
			array(
				'bgcolor', 
				'headbgcolor', 
				'headlink', 
				'headactivelink', 
				'headactivecolor', 
				'corelink', 
				'coretext', 
				'boxbgcolor', 
				'boxborder', 
				'boxlink', 
				'boxtext', 
				'boxhdbgcolor', 
				'boxhdborder', 
				'boxhdlink', 
				'boxhdtext'), 'post');
		$config = array(
			'bgcolor' => $color == '#ffffff' ? '' : $color, 
			'headbgcolor' => $headbgcolor == '#ffffff' ? '' : $headbgcolor, 
			'headlink' => $headlink == '#ffffff' ? '' : $headlink, 
			'headactivelink' => $headactivelink == '#ffffff' ? '' : $headactivelink, 
			'corelink' => $corelink == '#ffffff' ? '' : $corelink, 
			'coretext' => $coretext == '#ffffff' ? '' : $coretext, 
			'headactivecolor' => $headactivecolor == '#ffffff' ? '' : $headactivecolor, 
			'boxbgcolor' => $boxbgcolor == '#ffffff' ? '' : $boxbgcolor, 
			'boxborder' => $boxborder == '#ffffff' ? '' : $boxborder, 
			'boxlink' => $boxlink == '#ffffff' ? '' : $boxlink, 
			'boxtext' => $boxtext == '#ffffff' ? '' : $boxtext, 
			'boxhdbgcolor' => $boxhdbgcolor == '#ffffff' ? '' : $boxhdbgcolor, 
			'boxhdborder' => $boxhdborder == '#ffffff' ? '' : $boxhdborder, 
			'boxhdlink' => $boxhdlink == '#ffffff' ? '' : $boxhdlink, 
			'boxhdtext' => $boxhdtext == '#ffffff' ? '' : $boxhdtext, 
			'bgtile' => $request->get('bgtile', 'post'),
			'bgalign' => $request->get('bgalign', 'post'),
			'size' => $request->get('size', 'post'),
			'font' => $request->get('font', 'post'),
			'subjectsize' => $request->get('subjectsize', 'post'),
			'contentsize' => $request->get('contentsize', 'post'),
			'headbgtile' => $request->get('headbgtile', 'post'),
			'headbgalign' => $request->get('headbgalign', 'post'),
			'boxbgtile' => $request->get('boxbgtile', 'post'),
			'boxbgalign' => $request->get('boxbgalign', 'post'),
			'boxhdbgtile' => $request->get('boxhdbgtile', 'post'),
			'boxhdbgalign' => $request->get('boxhdbgalign', 'post')) + $config;
		$bo = new PwConfigSet('css');
		foreach ($config as $k => $v) {
			$bo->set($k, $v);
		}
		$bo->flush();
		$this->_compilerService()->doCompile($config);
		return $this->showMessage('success');
	}

	/**
	 * 删除图标，logo
	 */
	public function deleteAction(Request $request) {
		list($type, $path) = $request->get(array('type', 'path'));
		Tool::deleteAttach($path);
		Core::C()->setConfig('css', $type, '');
		$this->_compilerService()->doCompile();
		return $this->showMessage('success');
	}
	
	/**
	 * 删除应用目录
	 *
	 */
	public function delFolderAction(Request $request) {
		$folder = $request->get('folder');
		$dir = Wind::getRealDir('THEMES:' . $folder);
		WindFolder::clearRecur($dir, true);
		return $this->showMessage('success');
	}

	/**
	 * 设为默认
	 */
	public function defaultAction(Request $request) {
		$styleid = $request->get("styleid");
		if (($result = $this->_styleService()->useStyle($styleid)) instanceof ErrorBag) return $this->showError(
			$result->getError());
		return $this->showMessage('success');
	}

	/**
	 * 风格 - 扫描未安装的风格
	 */
	public function installAction(Request $request) {
		$addons = app('APPCENTER:service.srv.PwInstallApplication')->getConfig(
			'style-type');
		$themes = $this->_styleService()->getUnInstalledThemes();
		->with($themes, 'themes');
		->with($addons, 'addons');
	}
	
	/**
	 * 导出压缩包
	 *
	 */
	public function exportAction(Request $request) {
		list($type, $alias) = $request->get(array('type', 'alias'), 'get');
		$conf = app('APPCENTER:service.srv.PwInstallApplication')->getConfig(
			'style-type', $type);
		if (!$conf) return $this->showMessage('fail');
		Wind::import('LIB:utility.PwZip');
		$dir = Wind::getRealDir('THEMES:') . DIRECTORY_SEPARATOR . $conf[1] . DIRECTORY_SEPARATOR . $alias;
		if (!is_dir($dir)) return $this->showError('fail');
		$target = Wind::getRealPath('DATA:tmp.' . $alias . '.zip', true);
		PwApplicationHelper::zip($dir, $target);
		$this->getResponse()->setHeader('Content-type', 'application/x-zip-compressed');
		$this->getResponse()->setHeader('Content-Disposition', 'attachment; filename=' . $alias . '.zip');
		$this->getResponse()->setHeader('Expires', '0');
		$this->getResponse()->sendHeaders();
		readfile($target);
		WindFile::del($target);
		$this->getResponse()->sendBody();
		exit();
	}

	/**
	 * 风格预览
	 */
	public function previewAction(Request $request) {
		$id = $request->get("styleid");
		$addons = app('APPCENTER:service.srv.PwInstallApplication')->getConfig(
			'style-type');
		$style = $this->_styleDs()->getStyle($id);
		Tool::setCookie('style_preview', $style['alias'] . '|' . $style['style_type'], 20);
		$url = $addons[$style['style_type']][2];
		if ($style['style_type'] == 'space') {
			$url .= '?username=' . $this->loginUser->username;
		} /* else if ($style['style_type'] == 'forum') {
			$forums = app('forum.PwForum')->getForumOrderByType(false);
			$url .= '?fid=' . key($forums);
		} */
		return redirect(
			url($url, array(), '', 'pw'));
	}

	/**
	 * 安装未安装的风格列表
	 */
	public function doInstallAction(Request $request) {
		$themes = $request->get('themes');
		if (!$themes) return $this->showError('STYLE:style.illegal.themes', 'appcenter/style/install');
		
		foreach ($themes as $theme) {
			if (($result = $this->_install(Wind::getRealDir($theme, true))) instanceof ErrorBag) return $this->showError(
				$result->getError());
		}
		return $this->showMessage('success', 'appcenter/style/install');
	}

	/**
	 * 卸载
	 */
	public function uninstallAction(Request $request) {
		$styleid = $request->get('styleid');
		/* @var $uninstall PwUninstallApplication */
		if ($styleid[0] !== 'L') {
			$url = PwApplicationHelper::acloudUrl(
				array('a' => 'forward', 'do' => 'uninstallApp', 'appid' => $styleid));
			$info = PwApplicationHelper::requestAcloudData($url);
			if ($info['code'] !== '0')
				return $this->showError($info['msg']);
			else
				return $this->showMessage('success');
		} else {
			$uninstall = app('APPCENTER:service.srv.PwUninstallApplication');
			$r = $uninstall->uninstall($styleid);
			if ($r === true) return $this->showMessage('success');
			return $this->showError($r->getError());
		}
	}
	
	public function generateAction(Request $request) {
		$addons = app('APPCENTER:service.srv.PwInstallApplication')->getConfig(
			'style-type');
		->with($addons, 'addons');
		unset($addons['portal']);
		->with($addons, 'support');
	}
	
	public function doGenerateAction(Request $request) {
		list($style_type, $name, $alias, $description, $version, $pwversion, $website) =
		$request->get(array('style_type', 'name', 'alias', 'description', 'version', 'pwversion', 'website'), 'post');
		if (!$style_type || !$name || !$alias || !$version || !$pwversion) return $this->showError('APPCENTER:empty');
		if (!preg_match('/^[a-z][a-z0-9]+$/i', $alias)) return $this->showError('APPCENTER:illegal.alias');
		list($author, $email) = $request->get(array('author', 'email'), 'post');
		/* @var $srv PwGenerateStyle */
		$srv = app('APPCENTER:service.srv.PwGenerateStyle');
		$srv = new PwGenerateStyle();
		$srv->setStyle_type($style_type);
		$srv->setAlias($alias);
		$srv->setName($name);
		$srv->setDescription($description);
		$srv->setVersion($version);
		$srv->setPwversion($pwversion);
		$srv->setAuthor($author);
		$srv->setEmail($email);
		$srv->setWebsite($website);
		$r = $srv->generate();
		if ($r instanceof ErrorBag) return $this->showError($r->getError());
		return redirect('appcenter/style/doInstall', array('themes' => array($r)));
	}

	/**
	 * 重新安装流程
	 *
	 * @param string $manifestFile        	
	 * @param string $package        	
	 */
	private function _install($pack) {
		/* @var $install PwInstallApplication */
		Wind::import('APPCENTER:service.srv.PwInstallApplication');
		$install = new PwInstallApplication();
		/* @var $_install PwStyleInstall */
		$_install = app('APPCENTER:service.srv.do.PwStyleInstall');
		$conf = $install->getConfig('install-type', 'style');
		$manifest = $pack . '/Manifest.xml';
		if (!is_file($manifest)) return $this->showError('APPCENTER:install.mainfest.not.exist');
		$r = $install->initInstall($manifest);
		if ($r instanceof ErrorBag) return $this->showError($r->getError());
		$r = $_install->install($install);
		if ($r instanceof ErrorBag) return $this->showError($r->getError());
		$r = $_install->registeApplication($install);
		if ($r instanceof ErrorBag) return $this->showError($r->getError());
		$install->addInstallLog('packs', $pack);
		$install->addInstallLog('service', $conf);
		$fields = array();
		foreach ($install->getInstallLog() as $key => $value) {
			$_tmp = array(
				'app_id' => $install->getAppId(), 
				'log_type' => $key, 
				'data' => $value, 
				'created_time' => Tool::getTime(),
				'modified_time' => Tool::getTime());
			$fields[] = $_tmp;
		}
		app('APPCENTER:service.PwApplicationLog')->batchAdd($fields);
	}

	/**
	 * 上传
	 *
	 * @param string $key        	
	 * @return string
	 */
	private function _upload($key) {
		Wind::import('SRV:upload.action.PwIconUpload');
		Wind::import('LIB:upload.PwUpload');
		$bhv = new PwIconUpload($key, 'background/');
		$upload = new PwUpload($bhv);
		$r = $upload->execute();
		if ($r instanceof ErrorBag) return $this->showError($r->getError());
		return $bhv->getAttachInfo();
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
	 * @return PwStyle
	 */
	private function _styleDs() {
		return app('APPCENTER:service.PwStyle');
	}

	/**
	 *
	 * @return PwStyleService
	 */
	private function _styleService() {
		return app("APPCENTER:service.srv.PwStyleService");
	}
	
	/**
	 * @return PwCssCompile
	 */
	private function _compilerService() {
		return app('APPCENTER:service.srv.PwCssCompile');
	}
}

?>
