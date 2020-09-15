<?php
Wind::import('LIB:base.PwBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy$>
 * @author $Author$ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$ 
 * @package 
 */
class IndexController extends Controller{
	
	public function run() {
		$srv = app('forum.PwThread');
		$srv->syncHits();
		$_flag = 'cron_process';
		$_time = 3000;
		$servce = app('process.srv.PwProcessService');
		if (!$servce->lockProcess($_flag, $_time)) exit;
		if(!ini_get('safe_mode')){
			ignore_user_abort(true);
			set_time_limit(0);
		}
		$this->_getCronService()->runCron();
		$servce->unlockProcess($_flag);
		exit;
	}
	
	private function _getCronService() {
		return app('cron.srv.PwCronService');
	}
}
?>