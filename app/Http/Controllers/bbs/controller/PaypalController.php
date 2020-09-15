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
/**
 * 贝宝支付
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @license http://www.phpwind.com
 * @version $Id: PaypalController.php 24284 2013-01-25 03:28:25Z xiaoxia.xuxx $
 * @package forum
 */

class PaypalController extends Controller{
	
	protected $_var = array();
	protected $_conf = array();

	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		$this->_var = $request->getRequest();
		$this->_conf = Core::C('pay');
		
		if (!$this->_conf['ifopen']) {
			$this->paymsg($this->_conf['reason']);
		}
		if (!$this->_conf['paypal']) {
			$this->paymsg('onlinepay.settings.paypal.error');
		}
		if ($this->_conf['paypalkey'] != $this->_var['verifycode']) {
			$this->paymsg('onlinepay.auth.fail');
		}
    }

	public function run() {

		$order = app('pay.PwOrder')->getOrderByOrderNo($this->_var['invoice']);

		if (empty($order)) {
			$this->paymsg('onlinepay.order.exists.not');
		}
		$fee = $order['number'] * $order['price'];
	
		if ($fee != $this->_var['mc_gross']) {
			$this->paymsg('onlinepay.fail');
		}
		if ($this->_var['payment_status'] != 'Completed') {
			$this->paymsg('onlinepay.success');
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
		$dm->setState(2)->setPaymethod(3);
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