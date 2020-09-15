<?php
Wind::import('LIB:base.PwBaseController');
/**
 * the last known user to change this file in the repository  <$LastChangedBy: gao.wanggao $>
 * @author $Author: gao.wanggao $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: MylikeController.php 6836 2012-03-27 04:05:46Z gao.wanggao $
 * @package
 */
class MylikeController extends Controller{

	public function beforeAction($handlerAdapter) {
		parent::beforeAction($handlerAdapter);
		if ($this->loginUser->uid < 1) return redirect('u/login/run/'));
	}

	public function run() {
		$page = (int) $request->get('page', 'get');
		$tagid = (int) $request->get('tag', 'get');
		$perpage = 10;
		$page = $page > 1 ? $page : 1;
		$service = $this->_getBuildLikeService();
		$tagLists = $service->getTagsByUid($this->loginUser->uid);
		if ($tagid > 0) {
			$resource = $this->_getLikeService()->allowEditTag($this->loginUser->uid, $tagid);
			if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
			$count = $resource['number'];
			$logids = $service->getLogidsByTagid($tagid, $page, $perpage);
			$logLists = $service->getLogLists($logids);
		} else {
			list($start, $perpage) = Tool::page2limit($page, $perpage);
			$count = $this->_getLikeLogDs()->getLikeCount($this->loginUser->uid);
			$logLists = $service->getLogList($this->loginUser->uid, $start, $perpage);
		}
		
		// start
		$json = array();
		foreach ($logLists AS $_log) {
			$_log['tags'] = array_unique((array)$_log['tags']);
			if (!$_log['tags']) continue;
			$tagJson = array();
			foreach ((array)$_log['tags'] AS $_tagid) {
				if (!isset($tagLists[$_tagid]['tagname'])) continue;
				$tagJson[] = array(
					'id'=>$_tagid,
					'value'=>$tagLists[$_tagid]['tagname'],
				);
			}
			$json[] = array(
				'id'=>$_log['logid'],
				'items'=>$tagJson,
			);
		}
		//end
		$likeLists = $service->getLikeList();
		$likeInfos = $service->getLikeInfo();
		$hotBrand = $this->_getLikeService()->getLikeBrand('day1', 0, 10, true);
		$args = $tagid > 0 ? array("tag" => $tagid) : array();
		->with($args, 'args');
		->with($logLists, 'logLists');
		->with($likeLists, 'likeLists');
		->with($likeInfos, 'likeInfos');
		->with($tagLists, 'tagLists');
		->with($hotBrand, 'hotBrand');
		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($json, 'likeJson');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:like.mylike.run.title'), '', '');
		Core::setV('seo', $seoBo);
	}

	public function taAction(Request $request) {
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:like.mylike.ta.title'), '', '');
		Core::setV('seo', $seoBo);
	}

	public function dataAction(Request $request) {
		$page = (int) $request->get('page', 'get');
		$start = (int) $request->get('start', 'get');
		$start >= 100 && $start = 100;
		$perpage = 20;
		$_data = array();
		$logLists = $this->_getBuildLikeService()->getFollowLogList($this->loginUser->uid, $start, $perpage);
		$likeLists = $this->_getBuildLikeService()->getLikeList();
		$likeInfos = $this->_getBuildLikeService()->getLikeInfo();
		$replyInfos = $this->_getBuildLikeService()->getLastReplyInfo();
		foreach ($logLists as $k => $logList) {
			if (!isset($likeInfos[$logList['likeid']])) continue;
			$_data[$k]['fromid'] = $likeLists[$logList['likeid']]['fromid'];
			$_data[$k]['fromtype'] = $likeLists[$logList['likeid']]['typeid'];
			$_data[$k]['url'] = $likeInfos[$logList['likeid']]['url'];
			$_data[$k]['image'] = $likeInfos[$logList['likeid']]['image'];
			$_data[$k]['subject'] =  Security::escapeHTML($likeInfos[$logList['likeid']]['subject']);
			$_data[$k]['descrip'] =  Security::escapeHTML(strip_tags($likeInfos[$logList['likeid']]['content']));
			$_data[$k]['uid'] = $likeInfos[$logList['likeid']]['uid'];
			$_data[$k]['username'] = $likeInfos[$logList['likeid']]['username'];
			$_data[$k]['avatar'] = Tool::getAvatar($likeInfos[$logList['likeid']]['uid'], 'small');
			$_data[$k]['space'] = url(
				'space/index/run/?uid=' . $likeInfos[$logList['likeid']]['uid']);
			$_data[$k]['lasttime'] = Tool::time2str($likeInfos[$logList['likeid']]['lasttime'], 'auto');
			$_data[$k]['like_count'] = $likeInfos[$logList['likeid']]['like_count'];
			$_data[$k]['reply_pid'] = $replyInfos[$likeLists[$logList['likeid']]['reply_pid']];
			$_data[$k]['reply_uid'] = $replyInfos[$likeLists[$logList['likeid']]['reply_pid']]['uid'];
			$_data[$k]['reply_avatar'] = Tool::getAvatar($replyInfos[$likeLists[$logList['likeid']]['reply_pid']]['uid'],
				'small');
			$_data[$k]['reply_space'] = url(
				'space/index/run/?uid=' . $replyInfos[$likeLists[$logList['likeid']]['reply_pid']]['uid']);
			$_data[$k]['reply_username'] = $replyInfos[$likeLists[$logList['likeid']]['reply_pid']]['username'];
			$_data[$k]['reply_content'] =  Security::escapeHTML($replyInfos[$likeLists[$logList['likeid']]['reply_pid']]['content']);
			$_data[$k]['like_url'] = url(
				'like/mylike/doLike/?typeid=' . $_data[$k]['fromtype'] . '&fromid=' . $_data[$k]['fromid']);
		}
		->with($_data, 'html');
		return $this->showMessage('operate.success');
	}

	public function getTagListAction(Request $request) {
		$array = array();
		$lists = $this->_getLikeTagService()->getInfoByUid($this->loginUser->uid);
		->with($lists, 'data');
		return $this->showMessage('BBS:like.success');
	}

	/**
	 * 增加喜欢
	 *
	 */
	public function doLikeAction(Request $request) {
		$typeid = (int) $request->get('typeid', 'post');
		$fromid = (int) $request->get('fromid', 'post');
		if ($typeid < 1 || $fromid < 1) return $this->showError('BBS:like.fail');
		$resource = $this->_getLikeService()->addLike($this->loginUser, $typeid, $fromid);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		
		$needcheck = false;
		if($resource['extend']['needcheck'])  $needcheck = false;
		$data['likecount'] = $resource['likecount'];
		$data['needcheck'] = $needcheck;
		->with($data, 'data');
		return $this->showMessage('BBS:like.success');
	}

	/**
	 * 删除我的喜欢
	 *
	 * 如果喜欢内容总喜欢数小于1，同时删除喜欢内容
	 */
	public function doDelLikeAction(Request $request) {
		$logid = (int) $request->get('logid', 'post');
		if (!$logid) return $this->showError('BBS:like.fail');
		$resource = $this->_getLikeService()->delLike($this->loginUser->uid, $logid);
		if ($resource) return $this->showMessage('BBS:like.success');
		return $this->showError('BBS:like.fail');
	}
	
	/**
	 * 编辑喜欢所属分类
	 */
	public function doLogTagAction(Request $request) {
		$tagid = (int)$request->get('tagid', 'post');
		$type = (int)$request->get('type', 'post');
		$logid = (int)$request->get('logid', 'post');
		if (!$logid || !$tagid) return $this->showError('BBS:like.fail');
		$this->_getLikeService()->editLogTag($logid, $tagid, $type);
		return $this->showMessage('BBS:like.success');
	}
	
	/**
	 * 增加所属分类
	 * Enter description here ...
	 */
	public function doAddLogTagAction(Request $request) {
		$tagname = $request->get('tagname', 'post');
		$logid = (int)$request->get('logid', 'post');
		if (!$logid || !$tagname) return $this->showError('BBS:like.fail');
		$resource = $this->_getLikeService()->addTag($this->loginUser->uid,$tagname);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		$tagid = (int)$resource;
		$this->_getLikeService()->editLogTag($logid, $tagid, 1);
		->with(array('id'=>$tagid,'name'=>$tagname), 'data');
		return $this->showMessage('BBS:like.success');
	}

	/**
	 * 分类添加
	 * 
	 */
	public function doAddTagAction(Request $request) {
		$tagname = $request->get('tagname', 'post');
		if (!$tagname) return $this->showError('BBS:like.fail');
		$resource = $this->_getLikeService()->addTag($this->loginUser->uid,$tagname);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		->with(array('id'=>(int)$resource,'name'=>$tagname), 'data');
		return $this->showMessage('BBS:like.success');
	}

	/**
	 * 分类删除
	 *
	 */
	public function doDelTagAction(Request $request) {
		$tagid = (int) $request->get('tag', 'post');
		if (!$tagid) {
			return $this->showError('operate.fail');
		}
		$info = $this->_getLikeService()->allowEditTag($this->loginUser->uid, $tagid);
		if ($info instanceof ErrorBag) return $this->showError($info->getError());
		if (!$this->_getLikeTagService()->deleteInfo($tagid)) return $this->showError('BBS:like.fail');
		$this->_getLikeRelationsService()->deleteInfos($tagid);
		return $this->showMessage('BBS:like.success', true, 'like/mylike/run/');
	}

	/**
	 * 编辑分类
	 *
	 */
	public function doEditTagAction(Request $request) {
		$tagid = (int) $request->get('tag', 'post');
		$tagname = trim($request->get('tagname', 'post'));
		if (Tool::strlen($tagname) < 2) return $this->showError('BBS:like.tagname.is.short');
		if (Tool::strlen($tagname) > 10) return $this->showError('BBS:like.tagname.is.lenth');
		$tags = $this->_getLikeTagService()->getInfoByUid($this->loginUser->uid);
		$allow = false;
		foreach ($tags as $tag) {
			if ($tag['tagid'] == $tagid) $allow = true;
			if ($tag['tagname'] == $tagname) return $this->showError('BBS:like.fail.already.tagname');
		}
		if (!$allow) return $this->showError('BBS:like.fail');
		Wind::import('SRV:like.dm.PwLikeTagDm');
		$dm = new PwLikeTagDm($tagid);
		$dm->setTagname($tagname);
		$resource = $this->_getLikeTagService()->updateInfo($dm);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		->with(array('id'=>$tagid,'name'=>$tagname), 'data');
		return $this->showMessage('BBS:like.success');
	}

	private function _getLikeRelationsService() {
		return app('like.PwLikeRelations');
	}

	private function _getLikeTagService() {
		return app('like.PwLikeTag');
	}

	private function _getBuildLikeService() {
		return app('like.srv.PwBuildLikeService');
	}

	private function _getLikeService() {
		return app('like.srv.PwLikeService');
	}
	
	private function _getLikeLogDs() {
		return app('like.PwLikeLog');
	}
}
?>