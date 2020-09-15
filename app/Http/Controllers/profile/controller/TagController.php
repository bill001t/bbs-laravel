<?php
Wind::import('APPS:.profile.controller.BaseProfileController');
		
/**
 * 个性标签
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id$
 * @package wind
 */
class TagController extends BaseProfileController {
	private $perpage = 16;
	
	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		$this->setCurrentLeft();
		$tags = $this->_getService()->getUserTagList($this->loginUser->uid);
		$num = $this->_getRelationDs()->countByUid($this->loginUser->uid);
		$hotTags = $this->_getDs()->getHotTag($this->perpage, 0);
		$count = $this->_getDs()->countHotTag();
		$totalPage = ceil($count/$this->perpage);
		
		->with($totalPage, 'total');
		->with((10 - $num), 'allowNum');
		->with($tags, 'mytags');
		->with($hotTags, 'hotTags');
	}
	
	/**
	 * 添加标签
	 */
	public function doAddAction(Request $request) {
		$tag = $request->get('tagName', 'post');
		$result = $this->_getService()->addUserTagToUid($this->loginUser->uid, $tag, Tool::getTime());
		if ($result instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		->with(array('id' => $result, 'name' => $tag), 'data');
		return $this->showMessage('USER:tag.add.success');
	}
	
	/**
	 * 添加用户标签
	 */
	public function doAddByidAction(Request $request) {
		$tagid = $request->get('tagid');
		$result = $this->_getService()->addTagRelationWithTagid($this->loginUser->uid, $tagid, Tool::getTime());
		if ($result instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		return $this->showMessage('USER:tag.add.success');
	}
	
	/**
	 * 删除用户的标签
	 */
	public function doDeleteAction(Request $request) {
		$tagid = $request->get('tagid', 'post');
		if (!$tagid) {
			return $this->showError('operate.fail');
		}
		$result = $this->_getRelationDs()->deleteRelation($this->loginUser->uid, $tagid);
		if ($result instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		return $this->showMessage('USER:tag.delete.success');
	}
	
	/**
	 * 获得热门标签
	 */
	public function hotAction(Request $request) {
		$page = intval($request->get('start'));
		$page < 0 && $page = 0;
		$count = $this->_getDs()->countHotTag();
		$totalPage = ceil($count/$this->perpage);
		$page > $totalPage && $page = 1;
		list($start, $limit) = Tool::page2limit($page, $this->perpage);
		$hotTags = $this->_getDs()->getHotTag($this->perpage, $start);
		$list = array();
		foreach ($hotTags as $_item) {
			$list[] = array('tag_id' => $_item['tag_id'], 'name' => $_item['name']);
		}
		$data = array('list' => $list, 'page' => $page + 1);
		->with($data, 'data');
		return $this->showMessage('');
	}
	
	/**
	 * 标签的DS
	 *
	 * @return PwUserTag
	 */
	private function _getDs() {
		return app('usertag.PwUserTag');
	}
	
	/**
	 * 获得DS
	 * 
	 * @return PwUserTagRelation
	 */
	private function _getRelationDs() {
		return app('usertag.PwUserTagRelation');
	}
	
	/**
	 * 个人标签的服务
	 *
	 * @return PwUserTagService
	 */
	private function _getService() {
		return app('usertag.srv.PwUserTagService');
	}
	
	/* (non-PHPdoc)
	 * @see PwBaseController::setDefaultTemplateName()
	 */
	protected function setDefaultTemplateName($handlerAdapter) {
		return view('profile_tag');
	}
}