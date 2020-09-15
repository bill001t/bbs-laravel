<?php

namespace App\Http\Controllers\bbs\controller;

use App\Core\Tool;
use App\Core\MessageTool;
use App\Http\Controllers\Controller;
use App\Services\forum\bm\PwForumService;
use App\Services\forum\bm\PwThreadList;
use App\Services\forum\bm\threadList\PwNewThread;
use App\Services\forum\bs\PwThreadIndex;
use App\Services\seo\bo\PwSeoBo;
use Core;
use Illuminate\Http\Request;

class TenpayController extends Controller{
	
	protected $_var = array();
	protected $_conf = array();

	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		$this->_var = $request->getRequest();
		$this->_conf = Core::C('pay');
		
		if (!$this->_conf['ifopen']) {
			$this->paymsg($this->_conf['reason']);
		}
		if (!$this->_conf['tenpay'] || !$this->_conf['tenpaykey']) {
			$this->paymsg('onlinepay.settings.tenpay.error');
		}
		$arr = array('cmdno', 'pay_result', 'date', 'transaction_id', 'sp_billno', 'total_fee', 'fee_type', 'attach');
		$txt = '';
		foreach ($arr as $value) {
			$txt .= $value . '=' . $this->_var[$value] . '&';
		}
		$mac = strtoupper(md5($txt . 'key=' . $this->_conf['tenpaykey']));

		if ($mac != $this->_var['sign']) {
			$this->paymsg('onlinepay.auth.fail');
		}
		if ($this->_conf['tenpay'] != $this->_var['bargainor_id']) {
			$this->paymsg('onlinepay.tenpay.bargainorid.error');
		}
		if ($this->_var['pay_result'] != "0") {
			$this->paymsg('onlinepay.fail');
		}
    }

	public function run() {

		$order = app('pay.PwOrder')->getOrderByOrderNo($this->_var['transaction_id']);

		if (empty($order)) {
			$this->paymsg('onlinepay.order.exists.not');
		}
		if ($order['state'] == 2) {
			$this->paymsg('onlinepay.order.paid');
		}

		$className = Wind::import('SRV:pay.srv.action.PwPayAction' . $order['paytype']);
		if (class_exists($className)) {
			$class = new $className($order);
			$class->run();
		}

		Wind::import('SRV:pay.dm.PwOrderDm');
		$dm = new PwOrderDm($order['id']);
		$dm->setState(2)->setPaymethod(2);
		app('pay.PwOrder')->updateOrder($dm);

		$this->paymsg('onlinepay.success');
	}

	protected function paymsg($msg, $notify = 'success') {
		if (empty($_POST)) {
			if ('onlinepay.success' == $msg) {
				return $this->showMessage($msg, 'profile/credit/order', 2);
			}
			return $this->showError($msg, 'profile/credit/order', 2);
		}
		exit($notify);
	}
}