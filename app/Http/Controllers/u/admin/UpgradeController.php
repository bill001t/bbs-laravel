<?php
Wind::import('ADMIN:library.AdminBaseController');

/**
 * 后台用户组提升方案
 * 
 * @author peihong.zhangph <peihong.zhangph@aliyun-inc.com> Nov 21, 2011
 * @link http://www.phpwind.com
 * @copyright 2011 phpwind.com
 * @license
 * @version $Id: UpgradeController.php 28862 2013-05-28 03:20:14Z jieyin $
 */

class UpgradeController extends AdminBaseController {
	
	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {

		$config = Core::C()->getValues('site');
		$strategy = $config['upgradestrategy'];
		
		Wind::import('SRV:credit.bo.PwCreditBo');
		/* @var $pwCreditBo PwCreditBo */
		$pwCreditBo = PwCreditBo::getInstance();
		->with($pwCreditBo, 'credits');
		->with($strategy, 'member');
	}
	
	/**
	 * 配置增加表单处理器
	 *
	 * @return void
	 */
	public function dosaveAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');
		$member = $request->get('member', 'post');

		$strategy = array();
		Wind::import('SRV:credit.bo.PwCreditBo');
		/* @var $pwCreditBo PwCreditBo */
		$pwCreditBo = PwCreditBo::getInstance();
	
		foreach ($pwCreditBo->cType as $k => $v) {
			$vkey = 'credit' . $k;
			$member[$vkey] && $strategy[$vkey] = $member[$vkey];
		}
		foreach (array('postnum','onlinetime', 'digest') as $v) {
			$member[$v] && $strategy[$v] = $member[$v];
		}

		$config = new PwConfigSet('site');
		$config->set('upgradestrategy' , $strategy)->flush();

		return $this->showMessage('success', 'u/upgrade/run/', true);
	}
}