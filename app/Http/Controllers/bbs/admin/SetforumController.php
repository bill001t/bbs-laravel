<?php
Wind::import('ADMIN:library.AdminBaseController');
/**
 * 后台菜单管理操作类
 *
 * @author Qiong Wu <papa0924@gmail.com> 2011-10-21
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: SetforumController.php 28796 2013-05-24 05:38:40Z jieyin $
 * @package admin
 * @subpackage controller
 */
class SetforumController extends AdminBaseController {

	private $perpage = 20;
	
	/**
	 * 菜单管理主入口
	 * 
	 * @return void
	 */
	public function run() {
		
		$forumService = $this->_getFroumService();
		$map = $forumService->getForumMap();
		$catedb = $map[0];

		foreach ($catedb as $key => $value) {
			$forumList[$value['fid']] = $forumService->getForumsByLevel($value['fid'], $map);
		}

		->with($catedb, 'catedb');
		->with($forumList, 'forumList');
		->with($forumService->getForumOption(), 'option_html');
	}
	
	/**
	 * 添加版块、修改版块排序、修改版主等操作
	 * 
	 * @return void
	 */
	public function dorunAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		/**
		 * 修改版块资料
		 */
		list($vieworder, $manager) = $request->get(array('vieworder', 'manager'), 'post');
		//TODO 添加：先判断这些会员里是否含有身份不符合的用户，用户组1（游客）,2（禁止发言）,6（未验证用户）
		$_tmpManager = explode(',', implode(',', array_unique($manager)));
		$result = app('SRV:user.srv.PwUserMiscService')->filterForumManger($_tmpManager);
		if ($result instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		
		$editArray = array();
		Wind::import('SRV:forum.dm.PwForumDm');
		foreach ($vieworder as $key => $value) {
			$dm = new PwForumDm($key);
			$dm->setVieworder($value)->setManager($manager[$key]);
			if (($result = $dm->beforeUpdate()) instanceof ErrorBag) {
				return $this->showError($result->getError(), 'bbs/setforum/run/');
			}
			$editArray[] = $dm;
		}
		$pwForum = app('forum.PwForum');
		foreach ($editArray as $dm) {
			$pwForum->updateForum($dm, PwForum::FETCH_MAIN);
		}

		$forumset = array(
			'allowtype' => array('default'),
			'typeorder' => array('default' => 0)
		);
	
		/**
		 * 在真实版块下，添加子版
		 */
		list($new_vieworder, $new_forumname, $new_manager, $tempid) = $request->get(array('new_vieworder', 'new_forumname', 'new_manager', 'tempid'), 'post');
		$newArray = array();
		is_array($new_vieworder) || $new_vieworder = array();
		foreach ($new_vieworder as $parentid => $value) {
			foreach ($value as $key => $v) {
				if ($tempid[$parentid][$key] && $new_forumname[$parentid][$key]) {
					$dm = new PwForumDm();
					$dm->setParentid($parentid)
						->setName($new_forumname[$parentid][$key])
						->setVieworder($v)
						->setManager($new_manager[$parentid][$key])
						->setBasicSetting($forumset);
					if (($result = $pwForum->addForum($dm)) instanceof ErrorBag) {
						return $this->showError($result->getError(), 'bbs/setforum/run/');
					}
					$newArray[$tempid[$parentid][$key]] = $result;
				}
			}
		}
		
		/**
		 * 在虚拟版块下，添加子版
		 */
		list($temp_vieworder, $temp_forumname, $temp_manager) = $request->get(array('temp_vieworder', 'temp_forumname', 'temp_manager'), 'post');
		is_array($temp_vieworder) || $temp_vieworder = array();
		ksort($temp_vieworder);
		foreach ($temp_vieworder as $key => $value) {
			if (!isset($newArray[$key])) continue;
			foreach ($value as $k => $v) {
				if ($tempid[$key][$k] && $temp_forumname[$key][$k]) {
					$dm = new PwForumDm();
					$dm->setParentid($newArray[$key])
						->setName($temp_forumname[$key][$k])
						->setVieworder($v)
						->setManager($temp_manager[$key][$k])
						->setBasicSetting($forumset);
					if (($result = $pwForum->addForum($dm)) instanceof ErrorBag) {
						return $this->showError($result->getError(), 'bbs/setforum/run/');
					}
					$newArray[$tempid[$key][$k]] = $result;
				}
			}
		}
		app('forum.srv.PwForumMiscService')->correctData();

		return $this->showMessage('success', 'bbs/setforum/run/', true);
	}

	/**
	 * 编辑版块信息
	 * 
	 * @return void
	 */
	public function editAction(Request $request) {

		$fid = $request->get('fid');

		Wind::import('SRV:forum.bo.PwForumBo');
		$forum = new PwForumBo($fid, true);
		if (!$forum->isForum(true)) {
			return $this->showMessage('版块不存在', 'bbs/setforum/run', true);
		}
		
		$topicTypeService = app('forum.PwTopicType'); /* @var $topicTypeService PwTopicType */
		$topicTypes = $topicTypeService->getTopicTypesByFid($fid);
		->with($topicTypes, 'topicTypes');
		
		//权限相关
		$userGroupService = app('usergroup.PwUserGroups'); /* @var $userGroupService PwUserGroups */
		$userGroups = $userGroupService->getClassifiedGroups();
		$groupTypes = $userGroupService->getTypeNames();
		->with($userGroups,'userGroups');
		->with($groupTypes,'groupTypes');
		
		//seo
		$seo = app('seo.PwSeo')->getByModAndPageAndParam('bbs', 'thread', $fid);
		->with($seo, 'seo');
		
		//版块域名
		$forumroot = Core::C('domain', 'forum.root');
		if ($forumroot) {
			->with($forumroot, 'forumroot');
		}
		$domainKey = $forum->foruminfo['type'] == 'category' ? "bbs/cate/run?fid=$fid" : "bbs/thread/run?fid=$fid"; 
		$result = app('domain.PwDomain')->getByDomainKey($domainKey);
		$forumdomain = isset($result['domain']) ? $result['domain'] : '';
		->with($forumdomain, 'forumdomain');
		
		//版块风格
		$styles = app('APPCENTER:service.PwStyle')->getStyleListByType('forum', 0);
		->with($styles, 'styles');
		
		$p = array();
		foreach (array('allow_visit', 'allow_read', 'allow_post', 'allow_reply', 'allow_upload', 'allow_download') as $value) {
			$p[$value] = $forum->foruminfo[$value] ? explode(',', $forum->foruminfo[$value]) : array();
		}
		
		$creditset = $forum->foruminfo['settings_credit'] ? unserialize($forum->foruminfo['settings_credit']) : array();
		$password = $forum->foruminfo['password'] ? '******' : '';

		$_path = Wind::getRealDir('REP:mark.');
		$waterlist = WindFolder::read($_path);

		//forum list
		->with($this->_getFroumService()->getForumOption(), 'forumList');
		
		Wind::import('SRV:credit.bo.PwCreditBo');
		->with(PwCreditBo::getInstance()->cType, 'credittype');
		->with(app('forum.srv.PwThreadType')->getTtype(), 'threadtype');
		->with($p, 'p');
		->with($fid, 'fid');
		->with($forum->foruminfo, 'foruminfo');
		->with($forum->forumset, 'forumset');
		->with($creditset, 'creditset');
		->with($password, 'password');
		->with($this->_getFroumService()->getForumOption($forum->foruminfo['parentid']), 'option_html');
		->with($forum->foruminfo['type'] == 'category', 'isCate');
		->with($waterlist, 'waterlist');
	}

	public function doeditAction(Request $request) {

		$fid = $request->get('fid', 'post');
		if (!$fid) {
			return $this->showError('operate.fail');
		}

		list($copyFids,$copyItems) = $request->get(array('copy_fids','copyitems'));
		!$copyItems && $copyItems = array();
		Wind::import('SRV:forum.bo.PwForumBo');
		$forum = new PwForumBo($fid, true);
		if (!$forum->isForum(true)) {
			return $this->showMessage('版块不存在', 'bbs/setforum/run', true);
		}
		$this->_updateForums($forum, $copyFids, $copyItems);
		return $this->showMessage('success', 'bbs/setforum/edit/?fid=' . $fid, true);
	}

	public function uniteAction(Request $request) {
		$options = app('forum.srv.PwForumService')->getForumOption();
		->with($options, 'options');
	}

	public function douniteAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');
		$fid = $request->get('fid', 'post');
		$tofid = $request->get('tofid', 'post');

		Wind::import('SRV:forum.srv.operation.PwUniteForum');
		$srv = new PwUniteForum($fid, $tofid);
		if (($result = $srv->execute()) instanceof ErrorBag) {
			return $this->showError($result->getError());
		}

		return $this->showMessage('success', 'bbs/setforum/unite/', true);
	}

	private function _updateForums($forum, $copyFids = array(), $copyItems = array()) {
		$mainFid = $forum->fid;
		$fids = array($mainFid);
		$copyFids && $fids = array_merge($fids, $copyFids);

		list($forumname, $vieworder, $parentid, $descrip, $isshow, $isshowsub, $jumpurl, $seotitle, $seokeywords, $seodescription, $numofthreadtitle, $threadperpage, $readperpage, $newtime, $threadorderby, $minlengthofcontent, $locktime, $edittime, $allowtype, $typeorder, $contentcheck, $ifthumb, $thumbwidth, $thumbheight, $anticopy, $copycontent, $water, $waterimg, $allowhide, $allowsell, $anonymous, $manager, $creditset, $password, $allowvisit, $allowread, $allowpost, $allowreply, $allowupload, $allowdownload, $style) = $request->get(array('forumname', 'vieworder', 'parentid', 'descrip', 'isshow', 'isshowsub', 'jumpurl', 'seotitle', 'seokeywords', 'seodescription', 'numofthreadtitle', 'threadperpage', 'readperpage', 'newtime', 'threadorderby', 'minlengthofcontent', 'locktime', 'edittime', 'allowtype', 'typeorder', 'contentcheck', 'ifthumb', 'thumbwidth', 'thumbheight', 'anticopy', 'copycontent', 'water', 'waterimg', 'allowhide', 'allowsell', 'anonymous', 'manager', 'creditset', 'password', 'allowvisit', 'allowread', 'allowpost', 'allowreply', 'allowupload', 'allowdownload', 'style'));
		Wind::import('SRV:forum.bo.PwForumBo');
		Wind::import('SRV:forum.dm.PwForumDm');
		$pwforum = app('forum.PwForum');
		$copyItems = $copyItems ? array_flip($copyItems) : array();
		array_walk($copyItems, array($this,'_setCopyItems'));
		!$creditset && $creditset = array();
		foreach ($creditset as $key => $value) {
			!is_numeric($value['limit']) && $creditset[$key]['limit'] = '';
			foreach ($value['credit'] as $k => $v) {
				if (!is_numeric($v)) $creditset[$key]['credit'][$k] = '';
			}
		}
		
		$misc = false;
		foreach ($fids as $fid) {
			$flag = $fid == $mainFid;
			$tmpforum = $flag ? $forum : new PwForumBo($fid, true);
			if (!$tmpforum->isForum(true)) continue;

			$isCate = $tmpforum->foruminfo['type'] == 'category';
			$forumset = $tmpforum->forumset;
			($flag || $copyItems['jumpurl']) && $forumset['jumpurl'] = $jumpurl;
			($flag || $copyItems['numofthreadtitle']) && $forumset['numofthreadtitle'] = $numofthreadtitle ? intval($numofthreadtitle) : '';
			($flag || $copyItems['threadperpage']) && $forumset['threadperpage'] = $threadperpage ? intval($threadperpage) : '';
			($flag || $copyItems['readperpage']) && $forumset['readperpage'] = $readperpage ? intval($readperpage) : '';
			($flag || $copyItems['threadorderby']) && $forumset['threadorderby'] = $threadorderby;
			if ($isCate) {
				$tmpParentid = 0;
				$creditset = array();
			} else {
				$tmpParentid = $parentid;
				($flag || $copyItems['minlengthofcontent']) && $forumset['minlengthofcontent'] = $minlengthofcontent ? intval($minlengthofcontent) : '';
				($flag || $copyItems['locktime']) && $forumset['locktime'] = $locktime ? intval($locktime) : '';
				($flag || $copyItems['edittime']) && $forumset['edittime'] = $edittime ? intval($edittime) : '';
				if ($flag || $copyItems['allowtype']) {
					$forumset['allowtype'] = is_array($allowtype) ? $allowtype : array();
					$forumset['typeorder'] = array_map('intval', $typeorder);
				}
				if ($flag || $copyItems['allowhide']) {
					$forumset['allowhide'] = intval($allowhide);
					$forumset['allowsell'] = intval($allowsell);
					$forumset['anonymous'] = intval($anonymous);
				}
				($flag || $copyItems['contentcheck']) && $forumset['contentcheck'] = intval($contentcheck);
				if ($flag || $copyItems['ifthumb']) {
					$forumset['ifthumb'] = intval($ifthumb);
					$forumset['thumbwidth'] = $thumbwidth ? intval($thumbwidth) : '';
					$forumset['thumbheight'] = $thumbheight ? intval($thumbheight) : '';
				}
				($flag || $copyItems['water']) && $forumset['water'] = intval($water);
				($flag || $copyItems['waterimg']) && $forumset['waterimg'] = $waterimg;
				($flag || $copyItems['anticopy']) && $forumset['anticopy'] = intval($anticopy);
				($flag || $copyItems['copycontent']) && $forumset['copycontent'] = $copycontent;
	
				//主题分类设置
				list($topic_type, $force_topic_type, $topic_type_display) = $request->get(array('topic_type', 'force_topic_type', 'topic_type_display'));
				($flag || $copyItems['topic_type']) && $forumset['topic_type'] = intval($topic_type);
				($flag || $copyItems['force_topic_type']) && $forumset['force_topic_type'] = intval($force_topic_type);
				($flag || $copyItems['topic_type_display']) && $forumset['topic_type_display'] = intval($topic_type_display);
			}
			$dm = new PwForumDm($fid);
			if ($flag) {
				$dm->setName($forumname)
					->setVieworder($vieworder)
					->setParentid($tmpParentid);
					//上传版块图标 
					$icon = $this->_uploadImage('icon', $fid);
					//上传版块logo
					$logo = $this->_uploadImage('logo', $fid);
			}
			if ($icon && ($flag || $copyItems['icon'])) $dm->setIcon($icon['path']);
			if ($logo && ($flag || $copyItems['logo'])) $dm->setlogo($logo['path']);
			($flag || $copyItems['manager']) && $dm->setManager($manager);
			($flag || $copyItems['descrip']) && $dm->setDescrip($descrip);
			($flag || $copyItems['isshow']) && $dm->setIsshow($isshow);
			($flag || $copyItems['across']) && $dm->setAcross($request->get('across'));
			($flag || $copyItems['newtime']) && $dm->setNewtime($newtime);
			if ($flag || $copyItems['user_allows']) {
				$dm->setAllowVisit($allowvisit)
					->setAllowRead($allowread)
					->setAllowPost($allowpost)
					->setAllowReply($allowreply)
					->setAllowUpload($allowupload)
					->setAllowDownload($allowdownload);
			}
			
			($flag || $copyItems['creditset']) && $dm->setCreditSetting($creditset);
			($flag || $copyItems['style']) && $dm->setStyle($style);
			$dm->setBasicSetting($forumset);
			if ($password != '******' && ($flag || $copyItems['password'])) {
				$dm->setPassword($password);
			} elseif ($password == '******' && !$flag && $copyItems['password']) {
				$dm->setEncryptPassword($forum->foruminfo['password']);
			}
			$result = $pwforum->updateForum($dm);
			if ($result instanceof ErrorBag) {
				return $this->showError($result->getError());
			}
			//($flag || $copyItems['topictype']) && $this->doeditTopicType($fid);
			if ($flag) {
				$this->doeditTopicType($fid);
			} else if ($copyItems['topictype'] && !$flag){
				app('SRV:forum.srv.PwTopicTypeService')->copyTopicType($mainFid, $fid);
			}
			
			//seo
			($flag || $copyItems['seo']) && $this->_updateForumSeo($fid);
			//domain
			($flag || $copyItems['forumdomain']) && $this->_updateForumDomain($tmpforum);
			
			if ($flag && $tmpforum->foruminfo['parentid'] != $tmpParentid) {
				app('forum.srv.PwForumService')->updateForumStatistics($tmpParentid);
				app('forum.srv.PwForumService')->updateForumStatistics($tmpforum->foruminfo['parentid']);
				$misc = true;
			}
			if ($flag && $forumname != $tmpforum->foruminfo['name']) {
				$misc = true;
			}
			if (($flag || $copyItems['manager']) && $manager != trim($tmpforum->foruminfo['manager'], ',')) {
				$misc = true;
			}
		}
		if ($misc) {
			app('forum.srv.PwForumMiscService')->correctData();
		}
	}
	
	private function _setCopyItems(&$item,$key){
		$item = 1;
	}
	
	private function _updateForumSeo($fid){
		//seo
		$seo = $request->get('seo');
		Wind::import('SRV:seo.dm.PwSeoDm');
		$dm = new PwSeoDm();
		$dm->setMod('bbs')
		   ->setPage('thread')
		   ->setParam($fid)
		   ->setTitle($seo['title'])
		   ->setKeywords($seo['keywords'])
		   ->setDescription($seo['description']);
		app('seo.srv.PwSeoService')->batchReplaceSeoWithCache($dm);
	}
	
	private function _updateForumDomain($forum){
		//版块域名
		$fid = $forum->fid;
		list($forumdomain, $forumroot) = $request->get(array('forumdomain', 'forumroot'));
		$domainKey = $forum->foruminfo['type'] == 'category' ? "bbs/cate/run?fid=$fid" : "bbs/thread/run?fid=$fid"; 
		$oldDomain = app('domain.PwDomain')->getByDomainKey($domainKey);
		/* @var $srv PwDomainService */
		$srv = app('domain.srv.PwDomainService');
		if (!$forumdomain) {
			app('domain.PwDomain')->deleteByDomainKey($domainKey);
			if ($oldDomain) $srv->flushAll();
		}
		else {
			if ($forumroot) {
				$r = $srv->isDomainValid($forumdomain, $forumroot, $domainKey);
			}else {
				$r = $srv->isNameValid($forumdomain, $domainKey);
			}
			if ($r instanceof ErrorBag) return $this->showError($r->getError());
			Wind::import('SRV:domain.dm.PwDomainDm');
			$dm = new PwDomainDm();
			$dm->setDomain($forumdomain)
			->setDomainKey($domainKey)
			->setDomainType('forum')
			->setRoot($forumroot)
			->setFirst($forumdomain[0])
			->setId($fid);
			app('domain.PwDomain')->replaceDomain($dm);
			if (!$oldDomain || $oldDomain['domain'] != $forumdomain) $srv->flushAll();
		}
	}
	
	public function editnameAction(Request $request) {
		list($fid, $name) = $request->get(array('fid', 'name'));

		Wind::import('SRV:forum.dm.PwForumDm');
		$pwforum = app('forum.PwForum');
		$dm = new PwForumDm($fid);
		$dm->setName($name);
		$result = $pwforum->updateForum($dm);

		return $this->showMessage('success', 'bbs/setforum/edit/?fid=' . $fid, true);
	}

	/**
	 * 搜索版块名称 for ajax
	 */
	public function searchforumAction(Request $request){
		list($keyword) = $request->get(array('keyword'));
		$pwforum = app('forum.PwForum');
		$data = $pwforum->searchForum($keyword);
		if (!$data || !is_array($data)){
			return $this->showError('FORUM:searchforum.notfound');
		} else {
			->with($data, 'data');
			return $this->showMessage('FORUM:searchforum.success');
		}
	}
	
	public function deletetopictypeAction(Request $request){
		list($id) = $request->get(array('id'), 'get');
		$topicTypeService = app('forum.PwTopicType'); /* @var $topicTypeService PwTopicType */
		$topicTypeService->deleteTopicType($id);
		return $this->showMessage('FORUM:topictype.delete.success');
	}
	
	/**
	 * 删除一个版块
	 */
	public function deleteforumAction(Request $request) {
		
		$fid = $request->get('fid');

		Wind::import('SRV:forum.srv.operation.PwDeleteForum');
		$srv = new PwDeleteForum($fid, new PwUserBo($this->loginUser->uid));
		if (($result = $srv->execute()) instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		$foruminfo = $srv->forum->foruminfo;
		$foruminfo['logo'] && Tool::deleteAttach($foruminfo['logo']);
		$foruminfo['icon'] && Tool::deleteAttach($foruminfo['icon']);
		return $this->showMessage('success', 'bbs/setforum/run/', true);
	}
	
	/**
	 * 删除板块logo
	 */
	public function deletelogoAction(Request $request) {

		$fid = $request->get('fid');

		Wind::import('SRV:forum.bo.PwForumBo');
		$forum = new PwForumBo($fid, true);
		if (!$forum->isForum(true)) {
			return $this->showMessage('版块不存在', 'bbs/setforum/run', true);
		}

		Wind::import('SRV:forum.dm.PwForumDm');
		$dm = new PwForumDm($fid);
		$dm->setLogo('');
		$pwforum = app('forum.PwForum');
		$pwforum->updateForum($dm);

		Tool::deleteAttach($forum->foruminfo['logo']);

		return $this->showMessage('success');
	}
	
	/**
	 * 删除板块icon
	 */
	public function deleteiconAction(Request $request) {

		$fid = $request->get('fid');

		Wind::import('SRV:forum.bo.PwForumBo');
		$forum = new PwForumBo($fid, true);
		if (!$forum->isForum(true)) {
			return $this->showMessage('版块不存在', 'bbs/setforum/run', true);
		}

		Wind::import('SRV:forum.dm.PwForumDm');
		$dm = new PwForumDm($fid);
		$dm->setIcon('');
		$pwforum = app('forum.PwForum');
		$pwforum->updateForum($dm);

		Tool::deleteAttach($forum->foruminfo['icon']);

		return $this->showMessage('success');
	}
	
	/**
	 * 保存主题分类
	 * 
	 * @param $fid
	 */
	protected function doeditTopicType($fid){
		//主题分类
		list($t_vieworder, $t_name, $t_logo, $t_issys) = $request->get(array('t_vieworder', 't_name', 't_logo', 't_issys'), 'post');
		list($t_new_vieworder, $t_new_name, $t_new_logo, $t_new_issys) = $request->get(array('t_new_vieworder', 't_new_name', 't_new_logo', 't_new_issys'), 'post');
		list($t_new_sub_vieworder, $t_new_sub_name, $t_new_sub_logo, $t_new_sub_issys) = $request->get(array('t_new_sub_vieworder', 't_new_sub_name', 't_new_sub_logo', 't_new_sub_issys'),'post');
		
		is_array($t_name) || $t_name = array();
		is_array($t_new_name) || $t_new_name = array();
		is_array($t_new_sub_name) || $t_new_sub_name = array();

		Wind::import('SRV:forum.dm.PwTopicTypeDm');
		$topicTypeService = app('forum.PwTopicType'); /* @var $topicTypeService PwTopicType */
		
		//$logos = $this->_uploadTopicTypeIcon();
		$logos = array(); //TODO图标功能暂取消
		/* 更新原有 */
		$updateTopicTypes = array(); //待更新topicType Dm
		foreach ($t_name as $k=>$v) {
			$dm = new PwTopicTypeDm($k);
			$dm->setFid($fid)
				->setVieworder($t_vieworder[$k])
				->setName($t_name[$k])
				->setIsSystem($t_issys[$k]);
			$logos['t_logo'][$k] && $dm->setLogo($logos['t_logo'][$k]['filename']);
			$result = $dm->beforeUpdate();
			if ($result instanceof ErrorBag) {
				return $this->showError($result->getError());
			}
			$updateTopicTypes[] = $dm;
		}
		
		/* 新增主题分类 */
		$newTopicTypes = array();
		if (!$t_new_name) $t_new_name = array();
		foreach ($t_new_name as $k=>$v) {
			if (!$v) continue;
			$dm = new PwTopicTypeDm();
			$dm->setFid($fid)
				->setVieworder($t_new_vieworder[$k])
				->setName($t_new_name[$k])
				->setIsSystem($t_new_issys[$k]);
			$logos['t_new_logo'][$k] && $dm->setLogo($logos['t_new_logo'][$k]['filename']);
			$result = $dm->beforeAdd();
			if ($result instanceof ErrorBag) {
				return $this->showError($result->getError());
			}
			$newTopicTypes[$k] = $dm;
		}
		
		/* 新增二级主题分类 */
		$newSubTopicTypes = array();
		if (!$t_new_sub_name) $t_new_sub_name = array();
		foreach ($t_new_sub_name as $parentId=>$newSubs) {
			if (!is_array($newSubs)) continue;
			foreach ($newSubs as $k=>$v){
				$dm = new PwTopicTypeDm();
				$dm->setFid($fid)
					//->setParentId($parentid)
					->setVieworder($t_new_sub_vieworder[$parentId][$k])
					->setName($t_new_sub_name[$parentId][$k])
					->setIsSystem($t_new_sub_issys[$parentId][$k]);
				$logos['t_new_sub_logo'][$k] && $dm->setLogo($logos['t_new_sub_logo'][$k]['filename']);
				$result = $dm->beforeAdd();
				if ($result instanceof ErrorBag) {
					return $this->showError($result->getError());
				}
				$newSubTopicTypes[$parentId][] = $dm;
			}
		}
			
		/* 执行更新 */
		foreach ($updateTopicTypes as $v) {
			$topicTypeService->updateTopicType($v);
		}
		
		/* 执行新增 */
		$newTopicIds = array();
		foreach ($newTopicTypes as $k=>$v) {
			$topicId = $topicTypeService->addTopicType($v);
			is_numeric($topicId) && $newTopicIds[$k] = $topicId;
		}
		
		foreach ($newSubTopicTypes as $k=>$v) {
			if (!$k) continue;
			foreach ($v as $k2=>$v2) {
				$parentId = is_numeric($k) ? $k : $newTopicIds[$k];
				if (!$parentId) continue;
				$v2->setParentId($parentId);
				$topicTypeService->addTopicType($v2);
			}
		}
		//end 主题分类
	}
	
	private function _uploadTopicTypeIcon(){
 		Wind::import('SRV:upload.action.PwTopictypeUpload');
		Wind::import('LIB:upload.PwUpload');
		$bhv = new PwTopictypeUpload(16, 16);
		$upload = new PwUpload($bhv);
		if (($result = $upload->check()) === true) {
			$result = $upload->execute();
		}
		if ($result !== true) {
			return $this->showError($result->getError());
		}
		return $bhv->getAttachInfo();
	}
	
	private function _uploadImage($type, $fid) {
		Wind::import('SRV:upload.action.PwForumUpload');
		Wind::import('LIB:upload.PwUpload');
		$bhv = new PwForumUpload($type, $fid);
		$upload = new PwUpload($bhv);
		
		if (($result = $upload->check()) === true) {
			$result = $upload->execute();
		}
		if ($result !== true) {
			return $this->showError($result->getError());
		}
		$attachInfo = $bhv->getAttachInfo();
	
		return $attachInfo;
	}
	
	protected function _getFroumService() {
		return app('forum.srv.PwForumService');
	}
}
?>