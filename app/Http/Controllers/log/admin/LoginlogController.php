<?php
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('SRV:log.so.PwLogSo');

/**
 * 前台管理日志
 *
 * @author xiaoxia.xu<xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: LoginlogController.php 25604 2013-03-20 01:24:06Z gao.wanggao $
 * @package src.applications.log.admin
 */
class LoginlogController extends AdminBaseController {
	protected $perpage = 10;
	
	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		$page = intval($request->get('page'));
		($page < 1) && $page = 1;
		$logSo = new PwLogSo();
		$logSo->setEndTime($request->get('end_time'))
			->setStartTime($request->get('start_time'))
			->setCreatedUsername($request->get('created_user'))
			->setTypeid($request->get('typeid'))
			->setIp($request->get('ip'));
		/* @var $logDs PwLogLogin */
		$logDs = app('log.PwLogLogin');
		$count = $logDs->coutSearch($logSo);
		$list = array();
		if ($count > 0) {
			($page > $count) && $page = $count;
			$totalPage = ceil($count / $this->perpage);
			list($offset, $limit) = Tool::page2limit($page, $this->perpage);
			$list = $logDs->search($logSo, $limit, $offset);
		}
		->with($this->perpage, 'perpage');
		->with($list, 'list');
		->with($count, 'count');
		->with($page, 'page');
		->with($logSo->getSearchData(), 'searchData');
		->with($this->isFounder($this->loginUser->username), 'canClear');
		->with($this->_getLoginType(), 'types');
		return view('manage_login');
	}
	
	/**
	 * 清除三个月前操作
	 */
	public function clearAction(Request $request) {
		if (!$this->isFounder($this->loginUser->username)) return $this->showError('fail');
		$step = $request->get('step', 'post');
		if ($step != 2) return $this->showError('fail');
		list($year, $month) = explode('-', Tool::time2str(Tool::getTime(), 'Y-n'));
		if ($month > 3) {
			$month = $month - 3;
		} else {
			$month = 9 - $month;
			$year = $year - 1;
		}
		app('log.PwLogLogin')->clearLogBeforeDatetime(Tool::str2time($year . '-' . $month . '-1'));
		return $this->showMessage('success');
	}
	
	/**
	 * 返回登录的错误类型
	 *
	 * @return array
	 */
	private function _getLoginType() {
		return array(PwLogLogin::ERROR_PWD => '密码错误', PwLogLogin::ERROR_SAFEQ => '安全问题错误');
	}
}