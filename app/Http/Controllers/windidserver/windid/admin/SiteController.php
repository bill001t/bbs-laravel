<?php
defined('WEKIT_VERSION') || exit('Forbidden');

Wind::import('APPS:windid.admin.WindidBaseController');

/**
 * the last known user to change this file in the repository  <$LastChangedBy: jieyin $>
 * @author $Author: jieyin $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: SiteController.php 24709 2013-02-16 07:36:55Z jieyin $ 
 * @package 
 */
class SiteController extends WindidBaseController {
	
	public function run() {
		$config = Core::C()->getValues('site');
		->with($config, 'config');
	}
	
	public function dorunAction(Request $request) {
		$config = new PwConfigSet('site');
		$config->set('info.name', $request->get('infoName', 'post'))
			->set('info.url', $request->get('infoUrl', 'post'))
			->set('time.timezone', intval($request->get('timeTimezone', 'post')))
			->set('time.cv', intval($request->get('timecv', 'post')))
			->set('debug', $request->get('debug', 'post'))
			->set('cookie.path', $request->get('cookiePath'), 'post')
			->set('cookie.domain', $request->get('cookieDomain', 'post'))
			->set('cookie.pre', $request->get('cookiePre', 'pre'))
			->flush();
		return $this->showMessage('ADMIN:success');
	}
}
?>