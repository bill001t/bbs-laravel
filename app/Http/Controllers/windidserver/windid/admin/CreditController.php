<?php
Wind::import('APPS:windid.admin.WindidBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: jieyin $>
 * @author $Author: jieyin $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: CreditController.php 24718 2013-02-17 06:42:06Z jieyin $ 
 * @package 
 */
class CreditController extends WindidBaseController { 

	public function run() {
		$config = Core::C()->getValues('credit');
		->with($config['credits'], 'credits');
	}
	
	public function docreditAction(Request $request) {
		$credits = $request->get('credits', 'post');
		$newcredits = $request->get('newcredits', 'post');
		Wind::import('WSRV:config.srv.WindidCreditSetService');
		$srv = new WindidCreditSetService();
		$srv->setCredits($credits, $newcredits);

		$srv2 = app('WSRV:notify.srv.WindidNotifyService');
		$srv2->send('setCredits', array());
		return $this->showMessage('WINDID:success');
	}
	
	public function doDeletecreditAction(Request $request) {
		$creditId = (int) $request->get("creditId");
		if ($creditId < 5) return $this->showError('WINDID:fail');
		Wind::import('WSRV:config.srv.WindidCreditSetService');
		
		$srv = new WindidCreditSetService();
		if ((!$srv->deleteCredit($creditId))) {
			return $this->showError('WINDID:fail');
		}
		$srv2 = app('WSRV:notify.srv.WindidNotifyService');
		$srv2->send('setCredits', array());
		return $this->showMessage('WINDID:success');
	}
}
?>