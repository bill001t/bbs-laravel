<?php
Wind::import('APPCENTER:service.srv.helper.PwSystemHelper');
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('APPCENTER:service.srv.helper.PwFtpSave');
Wind::import('APPCENTER:service.srv.helper.PwSftpSave');
/**
 * 在线升级
 *
 * @author Shi Long <long.shi@alibaba-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: UpgradeController.php 28799 2013-05-24 06:47:37Z yetianshi $
 * @package appcenter
 */
class UpgradeController extends AdminBaseController {
	protected $upgrade_temp = 'DATA:upgrade.files.tmp';
	protected $fileList = array();
	protected $md5FileList = array();
	protected $localFileList = array();
	protected $version;
	/**
	 *
	 * @var PwSystemInstallation
	 */
	protected $installService = null;
	protected $status = array(
		1 => 'check', 
		2 => 'list', 
		3 => 'download', 
		4 => 'file', 
		5 => 'doupgrade', 
		6 => 'db', 
		7 => 'php', 
		8 => 'end');

	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if (!app('ADMIN:service.srv.AdminFounderService')->isFounder(
			$this->loginUser->username)) {
			return $this->showError('APPCENTER:upgrade.founder');
		}
		$this->installService = $this->_loadInstallation();
		$this->upgrade_temp = Wind::getRealPath($this->upgrade_temp, true);
		
		$action = $handlerAdapter->getAction();
		if (!in_array($action, array('run', 'check', 'select'))) {
			@set_time_limit(0);
			$r = @include $this->upgrade_temp;
			$this->_checkLegal($action, $r);
		}
	}

	public function afterAction($handlerAdapter) {
		$this->installService->flushLog();
	}

	public function run() {
		$r = $this->installService->checkEnvironment();
		if ($r instanceof ErrorBag) ->with(1, 'disable');
		$step = (int) Core::cache()->get('system_upgrade_step');
		// 继续上次的流程
		$action = '';
		if ($step > 1) {
			$step++;
			isset($this->status[$step]) && $action = $this->status[$step];
			->with(true/*$step < 6*/, 'recheck');
		}
		->with($action, 'action');
	}

	/**
	 * step 1: 请求升级信息，获取列表，写入upgrade.temp文件
	 */
	public function checkAction(Request $request) {
		$this->_clear();
		WindFolder::mkRecur(dirname($this->upgrade_temp));
		$r = $this->installService->checkUpgrade();
		$result = array();
		if (is_array($r)) {
			foreach ($r as $v) {
				$result[$v['version']] = $v;
			}
			WindFile::savePhpData(DATA_PATH . 'upgrade/info.tmp', $result);
		} else {
			->with($r, 'connect_fail');
		}
		->with($result, 'result');
	}

	public function selectAction(Request $request) {
		$version = $request->get('version');
		$upgradeInfo = @include DATA_PATH . 'upgrade/info.tmp';
		if (!isset($upgradeInfo[$version])) return $this->showError('APPCENTER:upgrade.illegal.request',
			'appcenter/upgrade/check');
		$r = $upgradeInfo[$version];
		$md5List = $fileList = array();
		foreach ($r['filelist'] as $v) {
			$md5List[] = key($v);
			$fileList[] = current($v);
		}
		$write_result = WindFile::savePhpData($this->upgrade_temp, 
			array('version' => $r['version'], 'filelist' => $fileList, 'md5list' => $md5List));
		if (!$write_result) return $this->showError(array('APPCENTER:upgrade.write.fail', array('data')));
		Core::cache()->set('system_upgrade_step', 1);
		unset($r['filelist']);
		$r['oldversion'] = NEXT_VERSION;
		$r['oldrelease'] = NEXT_RELEASE;
		$r['usezip'] = function_exists('gzinflate');
		Core::cache()->set('system_upgrade', $r);
		return redirect('appcenter/upgrade/list');
	}

	/**
	 * step2 :确认后，列出文件
	 */
	public function listAction(Request $request) {
		if (2 != Core::C('site', 'visit.state')) {
			->with(1, 'error');
		} else {
			->with($this->fileList, 'list');
			Core::cache()->set('system_upgrade_step', 2);
			->with(Core::cache()->get('system_upgrade'), 'status');
			PwSystemHelper::log('output the file list to upgrade', $this->version, true);
		}
	}

	/**
	 * step 3 : 下载
	 */
	public function downloadAction(Request $request) {
		$lang = Wind::getComponent('i18n');
		$status = Core::cache()->get('system_upgrade');
		$useFile = $request->get('usefile', 'get');
		$this->installService->useZip = $status['usezip'];
		if ($useFile) {
			$this->installService->useZip = 0;
			$status['usezip'] = 0;
			Core::cache()->set('system_upgrade', $status);
		}
		$success = 1;
		if ($this->installService->useZip) {
			$r = $this->installService->download($status['url'], $status['hash']);
			if ($r instanceof ErrorBag) {
				$success = 0;
				->with($lang->getMessage($r->getError()), 'msg');
			}
		} else {
			$step = (int) Core::cache()->get('system_upgrade_download_step');
			if ($step < count($this->fileList)) {
				$success = 0;
				$file = $this->fileList[$step];
				$r = $this->installService->download(substr($status['url'], 0, -4), 
					$this->md5FileList[$step], $file);
				if ($r instanceof ErrorBag) {
					->with($lang->getMessage($r->getError()), 'msg');
				} else {
					Core::cache()->set('system_upgrade_download_step', ++$step);
				}
				->with($this->fileList, 'fileList');
				->with($step, 'step');
			}
		}
		if ($success) {
			$fileList = $this->installService->sortDirectory($this->fileList);
			if ($fileList instanceof ErrorBag) return $this->showError($fileList->getError());
			WindFile::savePhpData($this->upgrade_temp, 
				array(
					'version' => $this->version, 
					'filelist' => $this->fileList, 
					'newfilelist' => $fileList));
			Core::cache()->set('system_upgrade_step', 3);
			PwSystemHelper::log('download file success', $this->version);
			return $this->showMessage('APPCENTER:upgrade.download.success', 'appcenter/upgrade/file', true);
		}
	}

	/**
	 * step 4 :--文件比对--
	 * 文件目录可写
	 * 文件md5比对
	 */
	public function fileAction(Request $request) {
		$success = 1;
		$useFtp = $request->get('ftp', 'post');
		if (!$useFtp) {
			$r = PwSystemHelper::checkFolder($this->localFileList);
			if ($r !== true) {
				list(, $folder) = $r;
				PwSystemHelper::log('folder write fail!' . $folder, $this->version);
				$success = 0;
				$lang = Wind::getComponent('i18n');
				$msg = $lang->getMessage('APPCENTER:upgrade.write.fail', array($folder));
				->with($msg, 'msg');
			}
		} else {
			try {
				$config = $request->get(array('server', 'port', 'user', 'pwd', 'dir', 'sftp'),
					'post', true);
				$ftp = $config['sftp'] ? new PwSftpSave($config) : new PwFtpSave($config);
			} catch (WindFtpException $e) {
				return $this->showError(array('APPCENTER:upgrade.ftp.fail', array($e->getMessage())),
					'appcenter/upgrade/file', true);
			}
			$ftp->close();
			Core::cache()->set('system_upgrade_ftp', $config);
		}
		if ($success) {
			list($change, $unchange, $new) = $this->installService->validateLocalFiles(
				$this->localFileList);
			->with(array('change' => $change, 'unchange' => $unchange, 'new' => $new));
			Core::cache()->set('system_upgrade_step', 4);
			PwSystemHelper::log('file md5 check success', $this->version);
		}
	}

	/**
	 * step 5 : 开始升级
	 * 备份源文件
	 * 覆盖文件
	 */
	public function doupgradeAction(Request $request) {
		$r = $this->installService->backUp($this->localFileList);
		if ($r instanceof ErrorBag) return $this->showError($r->getError());
		$useFtp = Core::cache()->get('system_upgrade_ftp');
		$r = $this->installService->doUpgrade($this->localFileList, $useFtp);
		if ($r instanceof ErrorBag) {
			$errorMsg = '上传失败！' . var_export($r->getError(), true);
			Tool::echoStr($errorMsg);
			exit();
		}
		Core::cache()->set('system_upgrade_step', 5);
		PwSystemHelper::log('file upgrade success', $this->version);
		header('Location:' . 'appcenter/upgrade/db');
		exit();
	}

	/**
	 * step 6 : 数据库更新操作
	 *
	 * 先执行update.sql,再跳转到update.php
	 */
	public function dbAction(Request $request) {
		$step = (int) Core::cache()->get('system_upgrade_db_step');
		$step || $this->installService->after($this->localFileList, 
			Core::cache()->get('system_upgrade_ftp'), $this->fileList);
		$sqlFile = Wind::getRealPath('PUBLIC:update.sql', true);
		$success = 1;
		if (!file_exists($sqlFile)) {
			Core::cache()->set('system_upgrade_step', 6);
			PwSystemHelper::log('no db update', $this->version);
			return redirect('appcenter/upgrade/php'));
		}
		$lang = Wind::getComponent('i18n');
		try {
			/* @var $db WindConnection */
			$db = Wind::getComponent('db');
			if (!$step) {
				$sqlArray = PwSystemHelper::sqlParser(WindFile::read($sqlFile), 
					$db->getConfig('charset', '', 'utf8'), $db->getTablePrefix(), 
					$db->getConfig('engine', '', 'MYISAM'));
				WindFile::savePhpData(DATA_PATH . 'upgrade/sql.tmp', $sqlArray);
			} else {
				$sqlArray = include DATA_PATH . 'upgrade/sql.tmp';
			}
			end($sqlArray);
			if ($step > key($sqlArray)) {
				Core::cache()->set('system_upgrade_step', 6);
				PwSystemHelper::log('db update success', $this->version);
				return redirect('appcenter/upgrade/php'));
			}
			$sql = $sqlArray[$step];
			if ($sql) {
				foreach ($sql as $v) {
					if (empty($v)) continue;
					if (preg_match(
						'/^ALTER\s+TABLE\s+`?(\w+)`?\s+(DROP|ADD)\s+(KEY|INDEX|UNIQUE)\s+([\w\(\),`]+)?/i', 
						$v, $matches)) {
						list($key, $fields) = explode('(', $matches[4]);
						$fields = trim($fields, '),');
						list($matches[3]) = explode(' ', $matches[3]);
						$matches[3] = trim(strtoupper($matches[3]));
						PwSystemHelper::log(
							$matches[1] . ' ' . str_replace('`', '', $key) . ' ' . ($fields ? str_replace(
								'`', '', $fields) : '') . ' ' . $matches[3], $this->version);
						PwSystemHelper::alterIndex(
							array(
								$matches[1], 
								str_replace('`', '', $key), 
								$fields ? str_replace('`', '', $fields) : '', 
								$matches[3], 
								$matches[2]), $db);
					} elseif (preg_match(
						'/^ALTER\s+TABLE\s+`?(\w+)`?\s+(CHANGE|DROP|ADD)\s+`?(\w+)`?/i', $v, 
						$matches)) {
						PwSystemHelper::log($matches[1] . ' ' . $matches[3], $this->version);
						PwSystemHelper::alterField(array($matches[1], $matches[3], $v), $db);
					} else {
						PwSystemHelper::log('execute sql ' . $v, $this->version);
						$db->execute($v);
					}
				}
			}
		} catch (Exception $e) {
			if ($e instanceof WindForwardException) throw $e;
			$success = 0;
			->with(1, 'error');
			PwSystemHelper::log('execute sql failed' . $e->getMessage(), $this->version);
			->with(
				$lang->getMessage('APPCENTER:upgrade.db.error', array(implode(';', $sql))), 'msg');
		}
		if ($success) {
			->with(
				$lang->getMessage('APPCENTER:upgrade.db.update', array($step, key($sqlArray))), 
				'msg');
		}
		Core::cache()->set('system_upgrade_db_step', ++$step);
	}

	/**
	 * 执行数据库脚本升级
	 */
	public function phpAction(Request $request) {
		$phps = $this->_getPhps();
		$step = Core::cache()->get('system_upgrade_php_step');
		$step || $step = 0;
		if ($phps && isset($phps[$step])) {
			$file = $phps[$step];
			Core::cache()->set('system_upgrade_php_step', ++$step);
			return redirect(
				'/' . '/' . $file . '?from=' . urlencode(
					url('appcenter/upgrade/php?step=' . $step)));
		} else {
			Core::cache()->set('system_upgrade_step', 7);
			return redirect('appcenter/upgrade/end'));
		}
	}

	/**
	 * 结束
	 */
	public function endAction(Request $request) {
		list($upgrade, $back) = $this->_backSuccess();
		app('hook.srv.PwHookRefresh')->refresh();
		app('SRV:cache.srv.PwCacheUpdateService')->updateAll();
		app('domain.srv.PwDomainService')->refreshTplCache();
		
		PwSystemHelper::log(
			'upgrade success, current version: ' . 'phpwind ' . NEXT_VERSION . ' release ' . NEXT_RELEASE, 
			$this->version);
		$this->_clear();
		Tool::setCookie('checkupgrade', '', -1);
		->with(
			array(
				'systeminfo' => 'phpwind ' . NEXT_VERSION . ' release ' . NEXT_RELEASE, 
				'back' => str_replace(ROOT_PATH, '', $back), 
				'upgrade' => str_replace(ROOT_PATH, '', $upgrade)));
	}

	private function _clear() {
		WindFolder::clearRecur(dirname($this->upgrade_temp), true);
		$useFtp = Core::cache()->get('system_upgrade_ftp');
		$phps = $this->_getPhps();
		$sql = PUBLIC_PATH . 'update.sql';
		if ($phps || file_exists($sql)) {
			if ($useFtp) {
				try {
					$ftp = $useFtp['sftp'] ? new PwSftpSave($useFtp) : new PwFtpSave($useFtp);
					$ftp->delete(str_replace(ROOT_PATH, '', $sql));
				} catch (WindFtpException $e) {}
			} else {
				WindFile::del($sql);
			}
			foreach ($phps as $php) {
				$file = PUBLIC_PATH . $php;
				if ($useFtp) {
					$file = str_replace(ROOT_PATH, '', $file);
					$ftp->delete($file);
				} else {
					WindFile::del($file);
				}
			}
			$ftp && $ftp->close();
		}
		WindFile::del(DATA_PATH . 'upgrade/sql.tmp');
		Core::cache()->batchDelete(
			array(
				'system_upgrade', 
				'system_upgrade_step', 
				'system_upgrade_db_step', 
				'system_upgrade_php_step', 
				'system_upgrade_ftp', 
				'system_upgrade_download_step', 
				'system_upgrade_info', 
				'system_upgrade_replace'));
	}

	/**
	 * 更新成功后备份
	 *
	 * @return multitype:string
	 */
	private function _backSuccess() {
		$data_dir = Wind::getRealDir('DATA:');
		$suffix = '[' . Tool::time2str(WEKIT_TIMESTAMP, 'Y-m-d Hi') . ']';
		$up_source = $data_dir . 'upgrade';
		$up_target = $data_dir . 'upgrade' . $suffix;
		PwApplicationHelper::copyRecursive($up_source, $up_target);
		$status = Core::cache()->get('system_upgrade');
		$ba_source = $data_dir . 'backup' . DIRECTORY_SEPARATOR . 'phpwind_' . str_replace('.', '', $status['oldversion']) . '_' . $status['oldrelease'];
		$ba_target = $data_dir . 'backup' . $suffix;
		PwApplicationHelper::copyRecursive($ba_source, $ba_target);
		WindFolder::clearRecur($up_source, true);
		WindFolder::clearRecur($ba_source, true);
		return array($up_target, $ba_target);
	}

	/**
	 *
	 * @return PwSystemInstallation
	 */
	private function _loadInstallation() {
		return app('APPCENTER:service.srv.PwSystemInstallation');
	}

	/**
	 * 检查是否合法请求
	 *
	 * @param unknown_type $action        	
	 * @param unknown_type $r        	
	 */
	private function _checkLegal($action, $r) {
		$step = Core::cache()->get('system_upgrade_step');
		$status = Core::cache()->get('system_upgrade');
		$legal = true;
		if (!$step || !$status) $legal = false;
		if ($action != $this->status[++$step]) $legal = false;
		if ($status['version'] != $r['version']) $legal = false;
		$this->version = $status['version'];
		if (!is_array($r['filelist'])) $legal = false;
		if (!$legal) {
			$this->_clear();
			return $this->showError('APPCENTER:upgrade.illegal.request', 'appcenter/upgrade/check');
		}
		$this->installService->target = $this->version;
		$this->fileList = $r['filelist'];
		$this->localFileList = isset($r['newfilelist']) ? $r['newfilelist'] : array();
		$this->md5FileList = $r['md5list'];
		->with($this->version, 'version');
	}

	private function _getPhps() {
		$files = WindFolder::read(PUBLIC_PATH, WindFolder::READ_FILE);
		$temp = array();
		foreach ($files as $file) {
			if (is_file(PUBLIC_PATH . $file) && '.php' === substr($file, -4) && !strncasecmp($file, 
				'update_', 7)) {
				$temp[substr($file, 7, 8)] = $file;
			}
		}
		ksort($temp);
		return array_values($temp);
	}
}

?>