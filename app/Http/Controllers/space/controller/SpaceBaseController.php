<?php
Wind::import('LIB:base.PwBaseController');
Wind::import('SRV:space.bo.PwSpaceModel');

/**
 * the last known user to change this file in the repository  <$LastChangedBy$>
 * @author $Author$ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$ 
 * @package 
 */
class SpaceBaseController extends Controller{
	
	public $space;
	
	/**
	 * (non-PHPdoc)
	 * @see src/library/base/PwBaseController::beforeAction()
	 */
	public  function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		$spaceUid = $request->get('uid','get');
		if ($spaceUid === '0') {
			return $this->showError('SPACE:space.guest');
		}
		$spaceUid = intval($spaceUid);
		if ($spaceUid < 1) {
			if ($userName = $request->get('username','get')) {
				$user = app('user.PwUser')->getUserByName($userName);
				$spaceUid = isset($user['uid'])? $user['uid'] : 0;
			} elseif ($this->loginUser->uid > 0) {
				$spaceUid = $this->loginUser->uid;
			} else {
				return $this->showError('SPACE:space.not.exist');
			}
		}
		
		$this->space = new PwSpaceModel($spaceUid);
	
		if (!$this->space->space['uid']) {
			$user = app('user.PwUser')->getUserByUid($spaceUid);
			if ($user) {
				app('space.dm.PwSpaceDm');
 				$dm = new PwSpaceDm($spaceUid);
 				$dm->setVisitCount(0);
				app('space.PwSpace')->addInfo($dm);
				$this->space = new PwSpaceModel($spaceUid);
			} else {
				//return redirect('u/login/run/'));
				return $this->showError('SPACE:space.not.exist');
			}
		}

		$this->space->setTome($spaceUid, $this->loginUser->uid);
		$this->space->setVisitUid($this->loginUser->uid);
		if (!$this->space->allowView('space')) return redirect('space/ban/run', array('uid' => $spaceUid)));
	}
	
	/**
	 * (non-PHPdoc)
	 * @see src/library/base/PwBaseController::afterAction()
	 */
	public function afterAction($handlerAdapter) {
		$this->setTheme('space', $this->space->space['space_style']);
		//$this->addCompileDir($this->space->space['space_style'] ? $this->space->space['space_style'] : Core::C('site', 'theme.space.default'));
		$host = $this->space->tome == PwSpaceModel::MYSELF ? '我' : 'Ta';
		->with($this->space, 'space');
		->with($host, 'host');
		$this->updateSpaceOnline();
		parent::afterAction($handlerAdapter);
	}
	
	/**
	 * 更新在线状态
	 */
	protected function updateSpaceOnline() {
		if ($this->loginUser->uid < 1) return false;
		$online = app('online.srv.PwOnlineService');
		$createdTime = $online->spaceOnline($this->space->spaceUid);
		if (!$createdTime) return false;
		$dm = app('online.dm.PwOnlineDm');
		$time = Tool::getTime();
		$dm->setUid($this->loginUser->uid)->setUsername($this->loginUser->username)->setModifytime($time)->setCreatedtime($createdTime)->setGid($this->loginUser->gid)->setRequest($this->_mca);
		app('online.PwUserOnline')->replaceInfo($dm);
		
		//脚印
		$service = app('space.srv.PwSpaceService');
		$service->signVisitor($this->space->spaceUid, $this->loginUser->uid);
		$service->signToVisitor($this->space->spaceUid, $this->loginUser->uid);	
	}
	
}
?>
