<?php
Wind::import('LIB:base.PwBaseController');

/**
 * 友情链接
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 * @package wind
 */
class IndexController extends Controller{

	public function run() {

	}
	
	public function doaddAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		list($name,$url,$descrip,$logo,$ifcheck,$contact) = $request->get(array('name','url','descrip','logo','ifcheck','contact'), 'post');

		Wind::import('SRC:service.link.dm.PwLinkDm');
		$linkDm = new PwLinkDm();
		$linkDm->setName($name);
		$linkDm->setUrl($url);		
		$linkDm->setDescrip($descrip);
		$linkDm->setLogo($logo);	
		$linkDm->setIfcheck(0);
		$linkDm->setContact($contact);
		$logo && $linkDm->setIflogo(1);
		if (($result = $this->_getLinkDs()->addLink($linkDm)) instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
    	return $this->showMessage('operate.success');
	}
	
	/**
	 * PwLink
	 *
	 * @return PwLink
	 */
	private function _getLinkDs() {
		return app('link.PwLink');
	}
}