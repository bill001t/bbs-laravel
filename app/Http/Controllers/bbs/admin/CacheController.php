<?php
Wind::import('ADMIN:library.AdminBaseController');
/**
 * 后台菜单管理操作类
 *
 * @author Qiong Wu <papa0924@gmail.com> 2011-10-21
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: CacheController.php 24341 2013-01-29 03:08:55Z jieyin $
 * @package admin
 * @subpackage controller
 */

class CacheController extends AdminBaseController {

	public function run() {}

	public function dorunAction(Request $request) {
		app('cache.srv.PwCacheUpdateService')->updateAll();
		return $this->showMessage('success');
	}

	public function doforumAction(Request $request) {
		app('forum.srv.PwForumMiscService')->countAllForumStatistics();
		return $this->showMessage('success');
	}

	/**
	 * css压缩
	 */
	public function buildCssAction(Request $request) {
		$debug = Core::C('site', 'css.compress');
		// 当前状态开启，则关闭它
		if ($debug) {
			$debug = 0;
		} else {
			$this->_compressCss();
			$debug = 1;
		}
		
		Core::C()->setConfig('site', 'css.compress', $debug);
		return $this->showMessage('success');
	}

	/**
	 * 更新css缓存
	 */
	public function doCssAction(Request $request) {
		$this->_compressCss();
		return $this->showMessage('success');
	}

	/**
	 * 更新hook缓存
	 */
	public function doHookAction(Request $request) {
		$r = app('hook.srv.PwHookRefresh')->refresh();
		if ($r instanceof ErrorBag) return $this->showError($r->getError());
		return $this->showMessage('success');
	}

	public function doTplAction(Request $request) {
		app('domain.srv.PwDomainService')->refreshTplCache();
		return $this->showMessage('success');
	}

	private function _compressCss() {
		Wind::import('LIB:compile.compiler.PwCssCompress');
		$compress = new PwCssCompress();
		$r = $compress->doCompile();
		if ($r instanceof ErrorBag) return $this->showError($r->getError());
	}
}
?>