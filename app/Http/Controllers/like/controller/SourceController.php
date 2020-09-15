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
class SourceController extends Controller{
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if ($this->loginUser->uid < 1) return redirect('u/login/run/'));
	}
	
	public function run() {
		
	}
	
	public function addlikeAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		$fromid = (int)$request->get('fromid','post');
		$fromApp = $request->get('app','post');
		$subject = $request->get('subject','post');
		$url = $request->get('url','post');
		if($fromid < 1 || empty($fromApp)) return $this->showError('BBS:like.fail');
		$source = $this->_getLikeSourceDs()->getSourceByAppAndFromid($fromApp, $fromid);
		$newId = isset($source['sid']) ? (int)$source['sid'] : 0;
		Wind::import('SRV:like.dm.PwLikeSourceDm');
		if ($newId < 1) {
			$dm = new PwLikeSourceDm();
			$dm->setSubject($subject)
				->setSourceUrl($url)
				->setFromApp($fromApp)
				->setFromid($fromid)
				->setLikeCount(0);
			$newId = $this->_getLikeSourceDs()->addSource($dm);
		} else {
			$dm = new PwLikeSourceDm($source['sid']);
			$dm->setLikeCount($source['like_count']);
			$this->_getLikeSourceDs()->updateSource($dm);
		}
		
		$resource = $this->_getLikeService()->addLike($this->loginUser, 9, $newId);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		->with($resource, 'data');
		return $this->showMessage('BBS:like.success');
	}
	
	private function _getLikeSourceDs() {
		return app('like.PwLikeSource');
	}
	
	private function _getLikeService() {
		return app('like.srv.PwLikeService');
	}
	
	
}
?>