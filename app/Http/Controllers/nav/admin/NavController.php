<?php
Wind::import('ADMIN:library.AdminBaseController');

/**
 * 导航模块控制器
 *
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ 
 * @version $Id: NavController.php 3897 2012-01-17 07:08:43Z gao.wanggao $ 
 * @package nav
 */

class NavController extends AdminBaseController {
	
	private $_navType =	'';
	
	/**
	 * 导航列表
	 *
	 * @return void
	 */
	public function run() {
		$this->_getNavType();
		$this->_navTab();
		$navList = $this->_getNavDs()->getNavByType($this->_navType, 2);
		->with($navList, 'navList');
		->with(Core::C('site','homeUrl'), 'homeUrl');
	}
	
	/**
	 * 导航批量修改处理器
	 *
	 * @return void
	 */
	public function dorunAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		$homeUrl = '';
		app('SRV:nav.dm.PwNavDm');
		$dms = $newDms = $datas = $newdatas = array();
		list($posts,$newposts,$navtype) = $request->get(array('data', 'newdata', 'navtype'), 'post');
		$homeid = $request->get('home', 'post');
		foreach ($posts AS $post) {
			if (!$post['name'] || !$navtype) continue;
			if ($navtype == 'my') {
				$router = $post['sign'];
			} else {
				$router = Wind::getComponent('router')->getRoute('pw')->matchUrl($post['link']);
			}
			app('SRV:nav.dm.PwNavDm');
			$dm = new PwNavDm($post['navid']);
			$dm->setName($post['name'])
				->setLink($post['link'])
				->setSign($router)
				->setOrderid($post['orderid'])
				->setIsshow($post['isshow']);
			$resource = $dm->beforeUpdate();
			if ($resource instanceof ErrorBag) {
				return $this->showError($resource->getError());
				break;
			}
			$dms[] = $dm;
			if ($post['navid'] == $homeid) $homeUrl = $post['link'];
		}
		if ($newposts) {
			foreach ($newposts AS $k=>$newpost) {
				if (!$newpost['name'] || !$navtype) continue;
				if ($navtype == 'my') {
					$router = $newpost['sign'];
				} else {				
					$router = Wind::getComponent('router')->getRoute('pw')->matchUrl($newpost['link']);
				}
				app('SRV:nav.dm.PwNavDm');
				list($isroot, $id) = explode('_', $k);
				$dm = new PwNavDm();
				if ($isroot == 'root'){
					$dm->setParentid(0);
				} elseif ($isroot == 'child') {
					if (is_numeric($newpost['parentid'])) {
						$dm->setParentid($newpost['parentid']);
					} else {
						$dm->setParentid((int)$resource);
					}
				}
				$dm->setName($newpost['name'])
					->setLink($newpost['link'])
					->setSign($router)
					->setOrderid($newpost['orderid'])
					->setIsshow($newpost['isshow'])
					->setTempid($newpost['tempid'])
					->setType($navtype);
				$resource = $this->_getNavDs()->addNav($dm);
				if ($resource instanceof ErrorBag) {
					return $this->showError($resource->getError());
					break;
				}
				if ($homeid == 'home_'.$k) $homeUrl = $newpost['link'];
			}
		}
		if ($homeUrl) {
			$config = new PwConfigSet('site');
			$homeRouter = Wind::getComponent('router')->getRoute('pw')->matchUrl($homeUrl);
			if ($homeRouter === false) return $this->showError('ADMIN:nav.out.link');
			$config->set('homeUrl', $homeUrl)
				->set('homeRouter', $homeRouter)
				->flush();
		}
		$this->_getNavDs()->updateNavs($dms);
		$this->_getNavService()->updateConfig();
		return $this->showMessage('ADMIN:success');
	}
	/**
	 * 导航修改表单
	 *
	 * @return void
	 */
	public function editAction(Request $request) {
		$navId = $request->get('navid', 'get');
		$navInfo = $this->_getNavDs()->getNav($navId);
		if (empty($navInfo)) {
			$resource = new ErrorBag('ADMIN:nav.edit.fail.error.navid');
			return $this->showError($resource->getError());
		}
		list($navInfo['color'], $navInfo['bold'], $navInfo['italic'], $navInfo['underline']) = explode('|', $navInfo['style']);	
		$navInfo['font'] = 'style=';
		!empty($navInfo['color']) && $navInfo['font'] .= 'color:'.$navInfo['color'].';';
		!empty($navInfo['bold']) && $navInfo['font'] .= 'font-weight:bold;';
		!empty($navInfo['italic']) && $navInfo['font'] .= 'font-style:italic;';
		!empty($navInfo['underline']) && $navInfo['font'] .= 'text-decoration:underline;';
		$this->_getNavType();
		$this->_navTab();
		->with($this->_getRootNavOption($navInfo['parentid']), 'navOption');
		->with($navInfo, 'navInfo');
	}
	
	/**
	 * 导航修改处理器
	 *
	 * @return void
	 */
	public function doeditAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		$keys = array('navid', 'type', 'parentid', 'name', 'link', 'image', 'fontColor', 'fontBold', 'fontItalic', 'fontUnderline', 'alt', 'target', 'orderid', 'isshow');
		list($navid, $type, $parentid, $name, $link, $image,$fontColor, $fontBold, $fontItalic, $fontUnderline, $alt, $target, $orderid, $isshow)= $request->get($keys, 'post');
		$router = Wind::getComponent('router')->getRoute('pw')->matchUrl($link);
		if (!$name || !$type) return $this->showError("ADMIN:nav.add.fail.strlen.name");
		app('SRV:nav.dm.PwNavDm');
		$dm = new PwNavDm($navid);
		$dm->setType($type)
			->setParentid($parentid)
			->setName($name)
			->setLink($link)
			->setStyle($fontColor, $fontBold, $fontItalic, $fontUnderline)
			->setAlt($alt)
			->setImage($image)
			->setTarget($target)
			->setOrderid($orderid)
			->setIsshow($isshow);
		if ($type != 'my') $dm->setSign($router);
		$resource = $this->_getNavDs()->updateNav($dm);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		$this->_getNavService()->updateConfig();
		return $this->showMessage('ADMIN:success');
	}
	
	/**
	 * 导航删除处理器
	 *
	 * @return void
	 */
	public function delAction(Request $request) {
		$navid = $request->get('navid', 'post');
		if (!$navid) {
			return $this->showError('operate.fail');
		}

		$resource = $this->_getNavDs()->delNav($navid);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		$this->_getNavService()->updateConfig();
		return $this->showMessage('ADMIN:success');
	}
	
	
	private function _getNavType() {
		$navType = $request->get('type', 'get');
		empty($navType) && $navType = 'main';
		$this->_navType = $navType;
	}
	
	private function _getNavDs() {
		return app('SRV:nav.PwNav');
	}

	private function _getNavService() {
		return app('SRV:nav.srv.PwNavService');
	}
	
	/**
	 * 导航公共TAB切换器
	 *
	 * @return void
	 */
	private function _navTab() {
		$navTypeList = $this->_getNavService()->getNavType();
		->with($this->_navType, 'navType');
		->with($navTypeList, 'navTypeList');
	}
	
	/**
	 * 组装顶级导航下拉选项
	 *
	 * @param int $select 当前选中的ID
	 * @return string
	 */
	private function _getRootNavOption($select='') {
		$option = '';
		$list = $this->_getNavDs()->getRootNav($this->_navType);
		foreach ($list AS $value) {
			$option.= '<option value="'.$value['navid'].'"';
			$option.= ($select == $value['navid']) ? 'selected' : ''; 
			$option.= '>'.$value['name'].'</option>';
		}
		return $option;
	}
}