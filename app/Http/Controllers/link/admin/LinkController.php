<?php
Wind::import('ADMIN:library.AdminBaseController');
/**
 * 添加友情链接
 *
 * @return void
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: LinkController.php 28814 2013-05-24 09:31:14Z jieyin $
 * @package controller.config
 */
class LinkController extends AdminBaseController {
	
	private $perpage = 20;

	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		$typeid = $request->get('typeid','get');
		//这里需要获取所有的链接列表，前台根据分类ID的筛选是js处理的
		$links = $this->_getLinkSrv()->getLinksList();
		$typesList = $this->_getLinkDs()->getAllTypes();
		
		$this->setTab('run');
		->with($typeid, 'typeid');
		->with($links, 'links');
		->with($typesList, 'typesList');
	}
	
	/**
	 * dorun
	 *
	 * @return void
	 */
	public function dorunAction(Request $request) {
		list($lid, $vieworder) = $request->get(array('lid', 'vieworder'), 'post');
		if (!$lid) return $this->showError('operate.select');
		Wind::import('SRC:service.link.dm.PwLinkDm');
		foreach ($lid as $_id) {
			if (!isset($vieworder[$_id])) continue;
			$linkDm = new PwLinkDm($_id);
			$linkDm->setVieworder($vieworder[$_id]);
			$this->_getLinkDs()->updateLink($linkDm);
		}
		return $this->showMessage('operate.success');
	}
	
	/**
	 * 添加友情链接
	 *
	 * @return void
	 */
	public function addAction(Request $request) {
		$types = $this->_getLinkSrv()->getAllLinkTypes();
		->with($types, 'types');
	}
	
	/**
	 * do添加友情链接
	 *
	 * @return void
	 */
	public function doaddAction(Request $request) {
		list($vieworder,$name,$url,$descrip,$logo,$ifcheck,$contact,$typeids) = $request->get(array('vieworder','name','url','descrip','logo','ifcheck','contact','typeids'), 'post');
		if (!$typeids) {
			return $this->showError('LINK:require_empty');
		}
		Wind::import('SRC:service.link.dm.PwLinkDm');
		$linkDm = new PwLinkDm();
		$linkDm->setVieworder($vieworder)
				->setName($name)
				->setUrl($url)
				->setDescrip($descrip)
				->setLogo($logo)
				->setIfcheck($ifcheck)
				->setContact($contact);
		$logo && $linkDm->setIflogo(1);
		if (($result = $this->_getLinkDs()->addLink($linkDm)) instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		foreach ($typeids as $v) {
			$this->_getLinkDs()->addRelation($result,$v);
		}
		
		return $this->showMessage('ADMIN:success');
	}
	
	/**
	 * 编辑友情链接
	 *
	 * @return void
	 */
	public function editAction(Request $request) {
		$types = $this->_getLinkSrv()->getAllLinkTypes();
		$lid = (int) $request->get('lid', 'get');
		$link = $this->_getLinkDs()->getLink($lid);
		$linkRelations = $this->_getLinkDs()->getRelationsByTypeId($lid);
		$typeIds = array();
		foreach ($linkRelations as $v) {
			$typeIds[] = $v['typeid'];
		}
		->with($typeIds, 'typeIds');
		->with($types, 'types');
		->with($link, 'link');
	}
	
	/**
	 * do编辑友情链接
	 *
	 * @return void
	 */
	public function doeditAction(Request $request) {
		list($vieworder,$name,$url,$descrip,$logo,$ifcheck,$contact,$typeids,$lid) = $request->get(array('vieworder','name','url','descrip','logo','ifcheck','contact','typeids','lid'), 'post');
		if (!$typeids) {
			return $this->showError('LINK:require_empty');
		}
		Wind::import('SRC:service.link.dm.PwLinkDm');
		$linkDm = new PwLinkDm($lid);
		$linkDm->setVieworder($vieworder)
				->setName($name)
				->setUrl($url)
				->setDescrip($descrip)
				->setLogo($logo)	
				->setIfcheck($ifcheck)
				->setContact($contact);
		$logo && $linkDm->setIflogo(1);
		if (($result = $linkDm->beforeUpdate()) instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		$this->_getLinkDs()->updateLink($linkDm);
		$this->_getLinkDs()->delRelationsByLid($lid);
		foreach ($typeids as $v) {
			$this->_getLinkDs()->addRelation($lid,$v);
		}

		return $this->showMessage('LINK:edit.success');
	}
	
	/**
	 * 删除友情链接
	 *
	 * @return void
	 */
	public function doDeleteAction(Request $request) {
		$lid = $request->get('lid', 'post');
		if (!$lid) return $this->showError('operate.select');
		if (($result = $this->_getLinkSrv()->batchDelete($lid)) instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		return $this->showMessage("operate.success");
	}
	
	/**
	 * 分类列表
	 *
	 * @return void
	 */
	public function typesAction(Request $request) {
		$typesList = $this->_getLinkSrv()->getAllLinkTypes();
		$this->setTab('editTypes');
		->with($typesList, 'typesList');
	}
	
	/**
	 * 编辑分类列表
	 *
	 * @return void
	 */
	public function dotypesAction(Request $request) {
		list($data,$newdata) = $request->get(array('data','newdata'), 'post');

		is_array($data) || $data = array();
		foreach ($data as $k => $v) {
			if (!$v['typename']) continue;
			if (Tool::strlen($v['typename']) > 6) {
				return $this->showError('Link:linkname.len.error');
			}
/*			$type = $this->_getLinkDs()->getTypeByName($v['typename']);
			if ($type && $type['typeid'] != $v['typeid']) {
				return $this->showError('Link:type.exist');
			}
			*/
			$this->_getLinkDs()->updateLinkType($v['typeid'],$v['typename'],$v['vieworder']);
		}

		is_array($newdata) || $newdata = array();
		if ($newdata) {
			foreach ($newdata as $v) {
				if (!$v['typename']) continue;
				if (Tool::strlen($v['typename']) > 6) {
					return $this->showError('Link:linkname.len.error');
				}
				$this->_getLinkDs()->addLinkType($v['typename'],$v['vieworder']);
			}
		}
		return $this->showMessage("LINK:edit.success");
	}
	
	/**
	 * 添加分类
	 *
	 * @return void
	 */
	public function addTypeAction(Request $request) {
	}
	
	/**
	 * do添加分类
	 *
	 * @return void
	 */
	public function doAddTypeAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		list($typename,$vieworder) = $request->get(array('typename','vieworder'), 'post');
		if (Tool::strlen($typename) > 6) {
			return $this->showError('Link:linkname.len.error');
		}
		$type = $this->_getLinkDs()->getTypeByName($typename);
		if ($type) {
			return $this->showError('Link:type.exist');
		}
		if (($result = $this->_getLinkDs()->addLinkType($typename, $vieworder)) instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		return $this->showMessage("ADMIN:success");
	}
	
	/**
	 * 删除分类
	 *
	 * @return void
	 */
	public function doDeleteTypeAction(Request $request) {
		$typeId = (int)$request->get('typeId','post');
		if (!$typeId) {
			return $this->showError('operate.fail');
		}

		if (($result = $this->_getLinkDs()->deleteType($typeId)) instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		return $this->showMessage("ADMIN:success");
	}
	
	/**
	 * 审核友情链接
	 *
	 * @return void
	 */
	public function checkAction(Request $request) {
		list($page, $perpage) = $request->get(array('page', 'perpage'));
		$page = $page ? $page : 1;
		$perpage = $perpage ? $perpage : $this->perpage;
		list($start, $limit) = Tool::page2limit($page, $perpage);
		list($count, $links) = $this->_getLinkSrv()->getCheckLinksList($start, $limit, 0);
		if ($count) {
			$typesList = $this->_getLinkDs()->getAllTypes();
			->with($typesList, 'typesList');
		}
		$this->setTab('check');
		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($links, 'links');
	}
	
	/**
	 * do审核友情链接
	 *
	 * @return void
	 */
	public function doCheckAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		list($data, $lid, $single) = $request->get(array('data', 'lid', 'signle'), 'post');
		if (!$lid) return $this->showError('operate.select');
		Wind::import('SRC:service.link.dm.PwLinkDm');
		foreach ($lid as $_id) {
			if (!isset($data[$_id])) continue;
			$linkDm = new PwLinkDm($_id);
			$linkDm->setVieworder($data[$_id]['vieworder']);
			$linkDm->setIfcheck(1);
			$rt = $this->_getLinkDs()->updateLink($linkDm);
			if ($rt instanceof ErrorBag) {
				return $this->showError($rt->getError());
			}
			$this->_getLinkDs()->delRelationsByLid($_id);
			$typeids = $single ? explode(',', $data[$_id]['typeid']) : $data[$_id]['typeid'];
			foreach ($typeids as $v) {
				$this->_getLinkDs()->addRelation($_id, $v);
			}
		}
		return $this->showMessage("operate.success");
	}
	
	/**
	 * 设置current
	 *
	 * @return void
	 */
	private function setTab($action) {
		$tabs = array('run' => '', 'editTypes' => '', 'check' => '');
		$tabs[$action] = 'current';
		->with($tabs, 'tabs');
	}
	
	/**
	 * PwLinkService
	 *
	 * @return PwLinkService
	 */
	private function _getLinkSrv() {
		return app('link.srv.PwLinkService');
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