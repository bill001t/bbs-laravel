<?php
Wind::import('LIB:base.PwBaseController');
Wind::import('SRV:space.bo.PwSpaceBo');
/**
 * the last known user to change this file in the repository  <$LastChangedBy$>
 * @author $Author$ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$ 
 * @package 
 */
class BanController extends Controller{
	public $space;
	
	public  function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		$spaceUid = (int)$request->get('uid','get');
		if ($spaceUid < 1){
			$userName = $request->get('username','get');
			$user = app('user.PwUser')->getUserByName($userName);
			$spaceUid = isset($user['uid'])? $user['uid'] : 0;
		}
		if ($spaceUid < 1) return redirect('u/login/run/'));
		$this->space = new PwSpaceBo($spaceUid);
		if (!$this->space->space['uid']){
			$user = app('user.PwUser')->getUserByUid($spaceUid);
			if ($user){
				app('space.dm.PwSpaceDm');
 				$dm = new PwSpaceDm($spaceUid);
 				$dm->setVisitCount(0);
				app('space.PwSpace')->addInfo($dm);
				$this->space = new PwSpaceBo($spaceUid);
			} else {
				return redirect('u/login/run/'));
			}
		}
		$this->space->setTome($spaceUid, $this->loginUser->uid);
		$this->space->setVisitUid($this->loginUser->uid);
		$this->setTheme('space', null);
		if ($this->space->allowView('space')) return redirect('space/index/run', array('uid' => $spaceUid)));
	}
	
	public function run() {
		->with($this->space, 'space');
	}
}
?>