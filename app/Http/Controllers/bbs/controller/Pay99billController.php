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
 * 快钱支付
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @license http://www.phpwind.com
 * @version $Id: Pay99billController.php 24284 2013-01-25 03:28:25Z xiaoxia.xuxx $
 * @package forum
 */

class Pay99billController extends Controller{
	
	protected $_var = array();
	protected $_conf = array();

	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		$this->_var = $request->getRequest();
		$this->_conf = Core::C('pay');
		
		if (!$this->_conf['ifopen']) {
			$this->paymsg($this->_conf['reason']);
		}
		if (!$this->_conf['99bill'] || !$this->_conf['99billkey']) {
			$this->paymsg('onlinepay.settings.99bill.error');
		}
		strlen($this->_conf['99bill']) == 11 && $this->_conf['99bill'] .= '01';

		$arr = array('payType','bankId','orderId','orderTime','orderAmount','dealId','bankDealId','dealTime', 'payAmount','fee','payResult','errCode');

		$txt = 'merchantAcctId='.$this->_conf['99bill'].'&version=v2.0&language=1&signType=1';
		foreach ($arr as $value) {
			$this->_var[$value] = trim($this->_var[$value]);
			if (strlen($this->_var[$value])>0) {
				$txt .= '&' . $value . '=' . $this->_var[$value];
			}
		}
		$mac = strtoupper(md5($txt . '&key=' . $this->_conf['99billkey']));
		
		if ($mac != strtoupper(trim($this->_var['signMsg']))) {
			$this->paymsg('onlinepay.auth.fail');
		}
		if ($this->_var['payResult'] != '10') {
			$this->paymsg('onlinepay.success');
		}
    }

	public function run() {

		$order = app('pay.PwOrder')->getOrderByOrderNo($this->_var['orderId']);

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
		$dm->setState(2)->setPaymethod(4);
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