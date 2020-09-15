<?php
defined('WEKIT_VERSION') || exit('Forbidden');

Wind::import('ADMIN:library.AdminBaseController');
Wind::import('SRV:credit.srv.PwCreditOperationConfig');
Wind::import('SRV:credit.bo.PwCreditBo');

/**
 * 积分设置
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: CreditController.php 4132 2012-02-11 05:35:07Z xiaoxia.xuxx $
 * @package src.products.admin.controller
 */
class CreditController extends AdminBaseController {

	/**
	 * 积分设置-展示页面
	 *
	 * @see WindController::run()
	 */
	public function run() {
		$this->setCurrentTab('run');
		$credits = $this->_getCreditService()->getCredit();
		ksort($credits);

		$creditConfig = Core::C()->getValues('credit');
		->with($credits, 'credits');
		->with($creditConfig['credits'] ? $creditConfig['credits'] : array(), 'localCredits');
	}

	/**
	 * 积分设置-保存设置操作
	 */
	public function doSettingAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		$credits = $request->get('credits', 'post');
		if (!is_array($credits) || empty($credits)) {
			return $this->showError("CREDIT:setting.dataError", "credit/credit/run");
		}
		$this->_getCreditService()->setCredits($credits, $request->get('newcredits', 'post'));
		return $this->showMessage("CREDIT:setting.success", "credit/credit/run");
	}

	/**
	 * 删除积分操作
	 */
	public function doDeleteAction(Request $request) {
		$creditId = (int) $request->get("creditId", 'post');
		if (!$creditId) {
			return $this->showError('operate.fail');
		}

		if ($creditId < 5) return $this->showError('CREDIT:setting.doDelete.fail', 'credit/credit/run');
		if (($result = $this->_getCreditService()->deleteCredit($creditId)) instanceof ErrorBag) {
			return $this->showError($result->getError(), "credit/credit/run");
		}
		return $this->showMessage("CREDIT:setting.doDelete.success", "credit/credit/run");
	}

	/**
	 * 积分策略-展示策略页面
	 */
	public function strategyAction(Request $request) {
		$this->setCurrentTab('strategy');
		// 所有的模块
		/* @var $config PwCreditOperationConfig */
		$config = PwCreditOperationConfig::getInstance();
		$creditConfig = Core::C()->getValues('credit');
		
		->with($config->getMap(), 'allModules');
		->with($config->getData(), 'moduleConfig');
		->with(PwCreditBo::getInstance(), 'creditBo');
		->with($creditConfig['strategy'] ? $creditConfig['strategy'] : array(), 'strategy');
	}

	/**
	 * 积分策略-编辑策略操作
	 */
	public function editStrategyAction(Request $request) {
		$info = $request->get('info');
		
		$creditConfig = Core::C()->getValues('credit');
		$strategy = $creditConfig['strategy'] ? $creditConfig['strategy'] : array();
		if (is_array($info)) {
			foreach ($info as $key => $value) {
				!is_numeric($value['limit']) && $info[$key]['limit'] = '';
				foreach ($value['credit'] as $k => $v) {
					!is_numeric($v) && $info[$key]['credit'][$k] = '';
				}
			}
			$strategy = array_merge($strategy, $info);
		}
		
		$config = new PwConfigSet('credit');
		$config->set('strategy', $strategy)->flush();
		return $this->showMessage('CREDIT:strategy.update.success', 'credit/credit/strategy');
	}

	/**
	 * 积分充值-展示页面
	 */
	public function rechargeAction(Request $request) {
		Wind::import('SRV:credit.bo.PwCreditBo');
		
		->with(PwCreditBo::getInstance(), 'creditBo');
		->with(Core::C('credit', 'recharge'), 'recharge');
		$this->setCurrentTab('recharge');
	}

	/**
	 * 积分充值-充值设置操作
	 */
	public function dorechargeAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		list($recharge, $ctype, $rate, $min) = $request->get(
			array('recharge', 'ctype', 'rate', 'min'));
		
		is_array($recharge) || $recharge = array();
		is_array($ctype) || $ctype = array();
		foreach ($ctype as $key => $value) {
			if ($rate[$key] && !isset($recharge[$value])) {
				$recharge[$value] = array(
					'rate' => intval($rate[$key]), 
					'min' => $min[$key] ? $min[$key] : '');
			}
		}
		$config = new PwConfigSet('credit');
		$config->set('recharge', $recharge)->flush();
		
		return $this->showMessage('operate.success');
	}

	/**
	 * 积分转换-展示页面
	 */
	public function exchangeAction(Request $request) {
		Wind::import('SRV:credit.bo.PwCreditBo');
		
		// print_r(Core::C('credit', 'exchange'));
		->with(PwCreditBo::getInstance(), 'creditBo');
		->with(Core::C('credit', 'exchange'), 'exchange');
		$this->setCurrentTab('exchange');
	}

	/**
	 * 积分转换-编辑操作
	 */
	public function doexchangeAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		list($exchange_old, $ifopen_old, $credit1, $credit2, $value1, $value2, $ifopen) = $request->get(
			array('exchange_old', 'ifopen_old', 'credit1', 'credit2', 'value1', 'value2', 'ifopen'));
		$old = array();
		$exchange = Core::C('credit', 'exchange');
		foreach ($exchange as $key => $value) {
			if (isset($exchange_old[$key])) {
				$exchange[$key]['ifopen'] = $ifopen_old[$key] ? 1 : 0;
			} else {
				unset($exchange[$key]);
			}
		}
		
		is_array($credit1) || $credit1 = array();
		foreach ($credit1 as $key => $value) {
			if (!$value || !$credit2[$key] || !$value1[$key] || !$value2[$key]) continue;
			if ($value == $credit2[$key]) {
				return $this->showError('CREDIT:exchange.fail.credit.same');
			}
			$vkey = $value . '_' . $credit2[$key];
			$exchange[$vkey] = array(
				'credit1' => $value, 
				'credit2' => $credit2[$key], 
				'value1' => $value1[$key], 
				'value2' => $value2[$key], 
				'ifopen' => $ifopen[$key] ? 1 : 0);
		}
		$config = new PwConfigSet('credit');
		$config->set('exchange', $exchange)->flush();
		
		return $this->showMessage('operate.success');
	}

	public function delexchangeAction(Request $request) {
		$id = $request->get('id', 'post');
		if (!$id) {
			return $this->showError('operate.fail');
		}

		$exchange = Core::C('credit', 'exchange');
		if (isset($exchange[$id])) {
			unset($exchange[$id]);
			$config = new PwConfigSet('credit');
			$config->set('exchange', $exchange)->flush();
		}
		return $this->showMessage('operate.success');
	}

	/**
	 * 积分转账设置页面
	 */
	public function transferAction(Request $request) {
		Wind::import('SRV:credit.bo.PwCreditBo');
		$transfer = Core::C('credit', 'transfer');
		->with(PwCreditBo::getInstance(), 'creditBo');
		->with($transfer ? $transfer : array(), 'transfer');
		$this->setCurrentTab('transfer');
	}

	/**
	 * 积分转账设置操作
	 */
	public function dotransferAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		list($ifopen, $rate, $min) = $request->get(array('ifopen', 'rate', 'min'));
		
		Wind::import('SRV:credit.bo.PwCreditBo');
		$creditBo = PwCreditBo::getInstance();
		$transfer = array();
		
		foreach ($creditBo->cType as $key => $value) {
			if (!$ifopen[$key] && !$rate[$key] && !$min[$key]) continue;
			$transfer[$key] = array(
				'ifopen' => $ifopen[$key] ? 1 : 0, 
				'rate' => $rate[$key] ? intval($rate[$key]) : '', 
				'min' => $min[$key] ? intval($min[$key]) : '');
		}
		$config = new PwConfigSet('credit');
		$config->set('transfer', $transfer)->flush();
		
		return $this->showMessage('operate.success');
	}

	/**
	 * 积分日志页面
	 */
	public function logAction(Request $request) {
		list($ctype, $time_start, $time_end, $award, $username, $uid) = $request->get(
			array('ctype', 'time_start', 'time_end', 'award', 'username', 'uid'));
		
		$page = $request->get('page');
		$page < 1 && $page = 1;
		$perpage = 20;
		list($offset, $limit) = Tool::page2limit($page, $perpage);
		
		Wind::import('SRV:credit.bo.PwCreditBo');
		Wind::import('SRV:credit.vo.PwCreditLogSc');
		Wind::import('SRV:credit.srv.PwCreditOperationConfig');
		
		$sc = new PwCreditLogSc();
		$url = array();
		if ($ctype) {
			$sc->setCtype($ctype);
			$url['ctype'] = $ctype;
		}
		if ($time_start) {
			$sc->setCreateTimeStart(Tool::str2time($time_start));
			$url['time_start'] = $time_start;
		}
		if ($time_end) {
			$sc->setCreateTimeEnd(Tool::str2time($time_end));
			$url['time_end'] = $time_end;
		}
		if ($award) {
			$sc->setAward($award);
			$url['award'] = $award;
		}
		if ($username) {
			$user = app('user.PwUser')->getUserByName($username);
			$sc->setUserid($user['uid']);
// 			$url['uid'] = $user['uid'];
			$url['username'] = $username;
		}
		if ($uid) {
			$sc->setUserid($uid);
			$url['uid'] = $uid;
		}
		$count = app('credit.PwCreditLog')->countBySearch($sc);
		$log = app('credit.PwCreditLog')->searchLog($sc, $limit, $offset);
		
		$this->setCurrentTab('log');
		->with(PwCreditBo::getInstance(), 'creditBo');
		->with(PwCreditOperationConfig::getInstance(), 'coc');
		->with($log, 'log');
		
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($count, 'count');
		->with($url, 'args');
	}

	/**
	 * 设置当前选项卡被选中
	 *
	 * @param string $action
	 *        	操作名
	 * @return void
	 */
	private function setCurrentTab($action) {
		$headerTab = array(
			'run' => '', 
			'strategy' => '', 
			'recharge' => '', 
			'exchange' => '', 
			'transfer' => '', 
			'log' => '');
		$headerTab[$action] = 'current';
		->with($headerTab, 'currentTabs');
	}

	/**
	 * 获得积分服务
	 *
	 * @return PwCreditSetService
	 */
	private function _getCreditService() {
		return app('credit.srv.PwCreditSetService');
	}

	/**
	 * 获得策略的服务对象
	 *
	 * @return PwCreditStrategyService
	 */
	private function _getCreditStrategyService() {
		return app('credit.srv.PwCreditStrategyService');
	}
}