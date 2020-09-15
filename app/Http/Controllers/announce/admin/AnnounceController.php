<?php
defined('WEKIT_VERSION') or exit(403);
Wind::import('ADMIN:library.AdminBaseController');
Wind::import('SRC:service.announce.dm.PwAnnounceDm');
/**
 * 管理公告页
 *
 * @author MingXing Sun <mingxing.sun@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: AnnounceController.php 28786 2013-05-23 09:57:58Z jieyin $
 * @package modules.admin
 */
class AnnounceController extends AdminBaseController {

	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
       $announceInfo = array();
       $page = $request->get('page');
       $page < 1 && $page = 1;
       $perpage = 10;
       list($start, $limit) = Tool::page2limit($page, $perpage);
       $pageCount = $this->_getPwAnnounceDs()->countAnnounce();
       $announceInfos = $this->_getPwAnnounceDs()->getAnnounceOrderByVieworder($limit, $start);
       $announceInfos = $this->_getPwAnnounceService()->formatAnnouncesUsername($announceInfos);
       ->with($announceInfos, 'announceInfos');
       ->with($page, 'page');
       ->with($perpage, 'perpage');
       ->with($pageCount, 'pageCount');
	}
	
	/**
	 * 添加公告
	 *
	 * @return void
	 */
	public function addAction(Request $request){}


	/**

	 * 添加公告处理

	 *

	 * @return void

	 */
	
	public function doAddAction(Request $request){
		$request->isPost() || return $this->showError('operate.fail');

		$url = $request->get('url', 'post');
		$dm = new PwAnnounceDm();
		$url && $url = WindUrlHelper::checkUrl($url);
		$dm->setContent($request->get('content', 'post'))
			->setEndDate($request->get('end_date', 'post'))
			->setStartDate($request->get('start_date', 'post'))

			->setSubject($request->get('subject', 'post'))

			->setTypeid($request->get('typeid', 'post'))
			->setUrl($url)
			->setUid($this->loginUser->uid)
			->setVieworder($request->get('vieworder', 'post'));
		
		if (($result = $this->_getPwAnnounceDs()->addAnnounce($dm)) instanceof ErrorBag){

			return $this->showError($result->getError());

		}
		return $this->showMessage('operate.success', 'announce/announce/run', true);
	}
	
	/**
	 * 公告列表页处理
	 *
	 * @return void
	 */
	public function doRunAction(Request $request){
		list($aid, $vieworders) = $request->get(array('aid', 'vieworder'), 'post');
		if(!$aid) return $this->showError('operate.select');
        foreach($aid as $_id){
        	if (!isset($vieworders[$_id])) continue;
        	$dm = new PwAnnounceDm($_id);
        	$dm->setVieworder($vieworders[$_id]);
        	$this->_getPwAnnounceDs()->updateAnnounce($dm);
        }
		return $this->showMessage('operate.success');
	}
	
	/**
	 * 通过多个公告ID批量删除多条公告
	 *
	 * @return void
	 */
	public function doBatchDeleteAction(Request $request){
		$aids = $request->get('aid', 'post');
		if (!$aids) return $this->showError('operate.select');
		if (!$this->_getPwAnnounceDs()->batchDeleteAnnounce($aids))return $this->showError('operate.fail');
		return $this->showMessage('operate.success');
	}
	
	/**
	 * 通过单个公告ID删除单条公告
	 *
	 * @return void
	 */
	public function doDeleteAction(Request $request){
		$aid = $request->get('aid', 'post');
		if(!$aid || !$this->_getPwAnnounceDs()->deleteAnnounce($aid))return $this->showError('operate.fail');
		return $this->showMessage("operate.success");
	}
	
	/**
	 * 编辑公告处理
	 *
	 * @return void
	 */
	public function doUpdateAction(Request $request){
		list($aid, $url) = $request->get(array('aid', 'url'),'post');
		if ($aid < 1) return $this->showError('operate.fail');

		$dm = new PwAnnounceDm($aid);
		$url && $url = WindUrlHelper::checkUrl($url);
		$dm->setContent($request->get('content', 'post'))
		   ->setEndDate($request->get('end_date', 'post'))
		   ->setStartDate($request->get('start_date', 'post'))
		   ->setSubject($request->get('subject', 'post'))
		   ->setTypeid($request->get('typeid', 'post'))
		   ->setUrl($url)
		   ->setUid($this->loginUser->uid)
		   ->setVieworder($request->get('vieworder', 'post'));
		if (($result = $this->_getPwAnnounceDs()->updateAnnounce($dm)) instanceof ErrorBag){
			return $this->showError($result->getError());
		}
		return $this->showMessage('operate.success', 'announce/announce/run');
	}
	
	/**
	 * 编辑公告
	 *
	 * @return void
	 */
	public function updateAction(Request $request){
		$showType = array();
		$aid = $request->get('aid', 'get');
		if($aid < 1) return $this->showError('ADMIN:fail');
		$announceInfo = $this->_getPwAnnounceDs()->getAnnounce($aid);
		$announceInfo['start_date'] && $announceInfo['start_date'] = Tool::time2str($announceInfo['start_date'], 'Y-m-d');
		$announceInfo['end_date'] && $announceInfo['end_date'] = Tool::time2str($announceInfo['end_date'], 'Y-m-d');
		$showType[$announceInfo['typeid']] = 'checked';
		->with($announceInfo, 'announceInfo');
		->with($showType, 'showType');
	}
	
	/**
	 * 加载PwAnnounceService Service 服务
	 *
	 * @return PwAnnounceService
	 */
	private function _getPwAnnounceService() {
		return app('announce.srv.PwAnnounceService');
	}
	
	/**
	 * 加载PwAnnounce Ds 服务
	 *
	 * @return PwAnnounce
	 */
	private function _getPwAnnounceDs() {
		return app('announce.PwAnnounce');
	}
}
