<?php
Wind::import('LIB:base.PwBaseController');
Wind::import('SRV:space.bo.PwSpaceBo');
/**
 * 我的空间
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: MyspaceController.php 28765 2013-05-23 03:05:46Z gao.wanggao $ 
 * @package 
 */
class MyspaceController extends Controller{
	
	public $spaceBo;
	
	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if ($this->loginUser->uid < 1)  return $this->showError('SPACE:user.not.login');
	}
	
	/**
	 * 空间设置
	 * @see wekit/wind/web/WindController::run()
	 */
	public function run(){
		$perpage = 6;
		$page = 1;
		$this->spaceBo = new PwSpaceBo($this->loginUser->uid);
		$list = $this->_getStyleDs()->getAllStyle('space');
		$addons = app('APPCENTER:service.srv.PwInstallApplication')->getConfig('style-type');
		
		//个性域名
		$domain_isopen = Core::C('domain', 'space.isopen');
		if ($domain_isopen) {
			$spaceroot = Core::C('domain', 'space.root');
			$domain = $this->_spaceDomainDs()->getDomainByUid($this->loginUser->uid);
			->with($spaceroot, 'spaceroot');
			->with($domain ? $domain : '', 'spacedomain');
		}

		->with($list, 'list');
		->with($perpage, 'perpage');
		->with(ceil(count($list) / $perpage), 'totalpage');
		->with(Core::url()->themes . '/' . $addons['space'][1], 'themeUrl');
		->with($this->spaceBo, 'space');
	}
	
	/**
	 * 判断域名是否可用
	 * Enter description here ...
	 */
	public function allowdomainAction(Request $request) {
		list($domain, $root) = $request->get(array('domain','root'));
		if (!$domain) return $this->showError('SPACE:domain.fail');
		$uid = $this->_spaceDomainDs()->getUidByDomain($domain);
		if ($uid && $uid != $this->loginUser->uid) return $this->showError('REWRITE:domain.exist');
		return $this->showMessage("success");
	}
	
	
	/**
	 * 空间基本信息处理
	 * Enter description here ...
	 */
	public function doEditSpaceAction(Request $request) {
		$spaceName = $request->get('spacename','post');
		$descrip = $request->get('descrip','post');
		
		//个性域名
		list($domain, $spaceroot) = $request->get(array('domain', 'spaceroot'));
		if ($spaceroot) {
			if (!$domain) {
				$this->_spaceDomainDs()->delDomain($this->loginUser->uid);
			}
			else {
				$uid = $this->_spaceDomainDs()->getUidByDomain($domain);
				if ($uid && $uid != $this->loginUser->uid) return $this->showError('REWRITE:domain.exist');
				$r = $this->_spaceDomainDs()->getDomainByUid($this->loginUser->uid);
				if (!$r) $this->_spaceDomainDs()->addDomain($this->loginUser->uid, $domain);
				else $this->_spaceDomainDs()->updateDomain($this->loginUser->uid, $domain);
			}
		}
		
		Wind::import('SRV:word.srv.PwWordFilter');
		$word = PwWordFilter::getInstance();
		if ($word->filter($spaceName))return $this->showError("SPACE:spacename.filter.fail");
		if ($word->filter($descrip)) return $this->showError("SPACE:descrip.filter.fail");
		
		Wind::import('SRV:space.dm.PwSpaceDm');
 		$dm = new PwSpaceDm($this->loginUser->uid);
 		$dm->setSpaceName($spaceName)
			->setSpaceDescrip($descrip)
			->setSpaceDomain($domain);
		$resource = $this->_getSpaceDs()->updateInfo($dm);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		return $this->showMessage("MEDAL:success");
	}
	
	/**
	 * 空间风格设置
	 * Enter description here ...
	 */
	public function doEditStyleAction(Request $request) {
		$styleid = $request->get('id','post');
		$style = $this->_getStyleDs()->getStyle($styleid);
		if (!$style) return $this->showError('SPACE:fail');
		Wind::import('SRV:space.dm.PwSpaceDm');
 		$dm = new PwSpaceDm($this->loginUser->uid);
 		$dm->setSpaceStyle($style['alias']);
		$resource = $this->_getSpaceDs()->updateInfo($dm);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		return $this->showMessage("MEDAL:success");
	}
	
	
	/**
	 * 空间背景设置
	 * Enter description here ...
	 */
	public function doEditBackgroundAction(Request $request) {
		$repeat = $request->get('repeat','post');
		$fixed = $request->get('fixed','post');
		$align = $request->get('align','post');
		$background = $request->get('background', 'post');
		$upload = $this->_uploadImage();
		$image  = isset($upload['path']) ? $upload['path'] : '';
		$this->spaceBo = new PwSpaceBo($this->loginUser->uid);
		if (!$image ){
			//list($image, $_repeat, $_fixed, $_align) = $this->spaceBo->space['back_image'];
			if (!$background) {
				$image = $repeat = $fixed = $align = '';
			} else {
				$image = $background;
			}
		}
		if (!in_array($repeat, array('no-repeat', 'repeat'))) $repeat = 'no-repeat';
		if (!in_array($fixed, array('fixed', 'scroll'))) $fixed = 'scroll';
		if (!in_array($align, array('left', 'right', 'center'))) $align = 'left';
		
		Wind::import('SRV:space.dm.PwSpaceDm');
 		$dm = new PwSpaceDm($this->loginUser->uid);
 		$dm->setBackImage($image, $repeat, $fixed, $align);
		$resource = $this->_getSpaceDs()->updateInfo($dm);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		return $this->showMessage("MEDAL:success");
	}
	
	public function delbackground() {
		
	}


	public function doreplyAction(Request $request) {

		$id = (int)$request->get('id','post');
		$content = $request->get('content', 'post');
		$transmit = $request->get('transmit', 'post');

		Wind::import('SRV:attention.srv.PwFreshReplyPost');
		$reply = new PwFreshReplyPost($id, $this->loginUser);

		if (($result = $reply->check()) !== true) {
			return $this->showMessage($result->getError());
		}
		$reply->setContent($content);
		$reply->setIsTransmit($transmit);

		if (($result = $reply->execute()) instanceof ErrorBag) {
			return $this->showMessage($result->getError());
		}
		if (!$reply->getIscheck()) {
			return $this->showError('BBS:post.reply.ischeck');
		}
		$content = app('forum.srv.PwThreadService')->displayContent($content, $reply->getIsuseubb(), $reply->getRemindUser());
		$this->setOutPut(Tool::getTime(), 'timestamp');
		$this->setOutPut($content, 'content');
		$this->setOutPut($this->loginUser->username, 'username');
	}
	
 	private function _uploadImage() {
 		Wind::import('SRV:upload.action.PwSpaceUpload');
		Wind::import('LIB:upload.PwUpload');
 		$bhv = new PwSpaceUpload($this->loginUser->uid);
		$upload = new PwUpload($bhv);
		if (($result = $upload->check()) === true) {
			$result = $upload->execute();
		}
		if ($result !== true) {
			return $this->showError($result->getError());
		}
		return $bhv->getAttachInfo();
 	}
	
	private function _getSpaceDs() {
		return app('SRV:space.PwSpace');
	}
	
	/**
	 * @return PwStyle
	 */
	private function _getStyleDs() {
		return app('APPCENTER:service.PwStyle');
	}
	
	/**
	 * @return PwSpaceDomain
	 */
	private function _spaceDomainDs() {
		return app('domain.PwSpaceDomain');
	}
}
?>