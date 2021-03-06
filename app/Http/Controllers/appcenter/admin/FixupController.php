<?php
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('APPCENTER:service.srv.helper.PwFtpSave');
Wind::import('APPCENTER:service.srv.helper.PwSftpSave');
Wind::import('APPCENTER:service.srv.helper.PwApplicationHelper');
/**
 * 安全补丁
 *
 * @author Shi Long <long.shi@alibaba-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: FixupController.php 24585 2013-02-01 04:02:37Z jieyin $
 * @package appcenter.admin
 */
class FixupController extends AdminBaseController {

	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if (!app('ADMIN:service.srv.AdminFounderService')->isFounder($this->loginUser->username)) {
			return $this->showError('APPCENTER:upgrade.founder');
		}
	}
	
	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		$result = $this->_service()->checkUpgrade();
		!is_array($result) && ->with($result, 'connect_fail');
		->with($result, 'patches');
	}

	/**
	 * 更新补丁
	 *
	 */
	public function doRunAction(Request $request) {
		$patchids = $request->get('patches', 'post');
		if (empty($patchids)) return $this->showError('APPCENTER:upgrade.choose.one');
		$result = $this->_service()->getOnlinePatchList();
		$ftp = Core::cache()->get('system_patch_ftp');
		foreach ($patchids as $id) {
			if (!isset($result[$id])) return $this->showError('APPCENTER:upgrade.patch.fail', 'appcenter/fixup/run', true);
			$patch = $result[$id];
			if (!$ftp) {
				$r = $this->_service()->writeAble($patch);
				if ($r === false) return redirect('appcenter/fixup/ftp');
			}
			$r = $this->_service()->install($patch);
			if ($r === false) return redirect('appcenter/fixup/ftp');
			if ($r instanceof ErrorBag) {
				$this->_service()->revert();
				return $this->showError($r->getError(), 'appcenter/fixup/run', true);
			}
		}
		$this->_service()->clear();
		return $this->showMessage('success', 'appcenter/fixup/run', true);
	}
	
	public function ftpAction(Request $request) {
		
	}
	
	public function doFtpAction(Request $request) {
		try {
			$config = $request->get(array('server', 'port', 'user', 'pwd', 'dir', 'sftp'), 'post', true);
			$ftp = $config['sftp'] ? new PwSftpSave($config) : new PwFtpSave($config);
		} catch (WindFtpException $e) {
			return $this->showError(array('APPCENTER:upgrade.ftp.fail', array($e->getMessage())));
		}
		$ftp->close();
		Core::cache()->set('system_patch_ftp', $config);
		return $this->showMessage('success', 'appcenter/fixup/run', true);
	}

	/**
	 *
	 * @return PwPatchUpdate
	 */
	private function _service() {
		return app('APPCENTER:service.srv.PwPatchUpdate');
	}
	
	/**
	 * @return PwPatch
	 */
	private function _ds() {
		return app('patch.PwPatch');
	}
}

?>