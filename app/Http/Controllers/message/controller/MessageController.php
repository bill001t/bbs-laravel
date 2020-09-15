<?php
Wind::import("LIB:utility.PwVerifyCode");
/**
 * 消息Controller
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 * @package wind
 */
class MessageController extends Controller{
	private $perpage = 20;
	
	public function beforeAction($handlerAdapter){
		parent::beforeAction($handlerAdapter);
		if (!$this->loginUser->isExists()) {
			return redirect('u/login/run'));
		//	return redirect('u/login/run'));
		}
		$controller = $handlerAdapter->getController();
		$action = $handlerAdapter->getAction();
		->with($action,'_action');
		->with($controller,'_controller');
	}
	
	/**
	 * 会话列表
	 * @see WindController::run()
	 */
	public function run() {		
		list($page, $perpage) = $request->get(array('page', 'perpage'));
		$page = $page ? $page : 1;
		$perpage = $perpage ? $perpage : $this->perpage;
		list($start, $limit) = Tool::page2limit($page, $perpage);
		list($count, $result) = $this->_getMessageService()->getDialogs($this->loginUser->uid,$start, $limit);
		$dialogs = array();
		foreach ($result as $v) {
			$v['last_message']['content'] = strip_tags($v['last_message']['content']);
			$v['last_message']['content'] = $this->_parseEmotion($v['last_message']['content']);
			$dialogs[] = $v;
		}
		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($dialogs, 'dialogs');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:mess.mess.run.title'), '', '');
		Core::setV('seo', $seoBo);
	}

	/**
	 * 发消息
	 *
	 * @return void
	 */
	public function addAction(Request $request) {
		// 检测权限
		$right = $this->_getMessageService()->checkAddMessageRight($this->loginUser);
		if ($right instanceof ErrorBag) {
			return $this->showError($right->getError());
		}
		$username = $request->get('username');
		if ($username) {
			!is_array($username) && $username = array($username);
			->with($username, 'username');
		}
		->with(in_array('sendmsg', (array)Core::C('verify', 'showverify')), 'verify');
	} 
	
	/**
	 * do发消息
	 *
	 * @return void
	 */
	public function doAddMessageAction(Request $request) {
		$right = $this->_getMessageService()->checkAddMessageRight($this->loginUser);
		if ($right instanceof ErrorBag) {
			return $this->showError($right->getError());
		}
		list($usernames,$content,$code) = $request->get(array('usernames','content', 'code'),'post');
		if (!$content) return $this->showError('MESSAGE:content.empty');
		$len = Tool::strlen($content);
		if ($len > 500) return $this->showError('MESSAGE:content.length.error');
		$countUser = count($usernames);
		(!is_array($usernames) || !$countUser) && return $this->showError('MESSAGE:user.empty');
		// 检测权限
		if ($countUser == 1 && $usernames[0] == $this->loginUser->username)  {
			return $this->showError('MESSAGE:send.to.myself');
		}
		
		if (in_array('sendmsg', (array)Core::C('verify', 'showverify'))) {
			if (false === $this->_getVerifyService()->checkVerify($code)) {
				return $this->showError('USER:verifycode.error');
			}
		}
		$result = $this->_getMessageService()->sendMessageByUsernames((array)$usernames,$content,$this->loginUser->uid);
		if ($result instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		return $this->showMessage('success','message/message/run');
	}
	
	/**
	 * 弹窗发消息
	 *
	 * @return void
	 */
	public function popAction(Request $request) {
		// 检测权限
		$right = $this->_getMessageService()->checkAddMessageRight($this->loginUser);
		if ($right instanceof ErrorBag) {
			return $this->showError($right->getError());
		}

		$uid = (int)$request->get('uid');
		if ($uid) {
			$userinfo = $this->_getUserDs()->getUserByUid($uid);
			$username = $userinfo['username'];
		} else {
			$username = $request->get('username');
		}
		if ($username) {
			!is_array($username) && $username = array($username);
			->with($username, 'username');
		}
		->with(in_array('sendmsg', (array)Core::C('verify', 'showverify')), 'verify');
	} 
	
	/**
	 * do发消息dialog
	 *
	 * @return void
	 */
	public function doAddDialogAction(Request $request) {
		// 检测权限
		$right = $this->_getMessageService()->checkAddMessageRight($this->loginUser);
		if ($right instanceof ErrorBag) {
			return $this->showError($right->getError());
		}
		list($username,$content,$code) = $request->get(array('username','content', 'code'),'post');
		!$content && return $this->showError('MESSAGE:content.empty');
		if (Tool::strlen($content) > 500) {
			return $this->showError('MESSAGE:content.length.error');
		}

		$result = $this->_getMessageService()->sendMessage($username,$content,$this->loginUser->uid);
		if ($result instanceof ErrorBag) {
			return $this->showError($result->getError());
		}
		return $this->showMessage('success');
	}
	
	/**
	 * 对话详细列表
	 *
	 * @return void
	 */
	public function dialogAction(Request $request) {
		list($page, $perpage, $dialogid) = $request->get(array('page', 'perpage', 'dialogid'));
		$page = $page ? $page : 1;
		$perpage = $perpage ? $perpage : $this->perpage;
		list($start, $limit) = Tool::page2limit($page, $perpage);
		$dialog = $this->_getMessageService()->getDialog($dialogid);
		if ($dialog['to_uid'] != $this->loginUser->uid) {
			return $this->showError('MESSAGE:dialog.error');
		}
		list($count, $messages) = $this->_getMessageService()->getDialogMessageList($dialogid, $limit, $start);

		//更新统计数
		$messageIds = array_keys($messages);
		$num = $this->_getWindid()->read($this->loginUser->uid,$dialog['dialog_id'],$messageIds);
		if ($num) {
			//$this->_getMessageService()->resetDialogMessages($dialog['dialog_id']);
			//$this->_getMessageService()->resetUserMessages($dialog['to_uid']);
			$this->_updateMessageCount($this->loginUser->uid,'-'.$num);
		}
		->with($count, 'count');
		->with($page, 'page');
		->with($perpage, 'perpage');
		->with($dialog, 'dialog');
		->with($messages, 'messages');
		->with(array('dialogid'=>$dialogid),'args');
		->with(in_array('sendmsg', (array)Core::C('verify', 'showverify')), 'verify');
	}
	
	/**
	 * 删除单条消息
	 *
	 * @return void
	 */
	public function deletemessageAction(Request $request) {
		$dialogId = (int)$request->get('dialogid');
		$messageId = (int)$request->get('messageid');
		if (!$dialogId || !$messageId) {
			return $this->showError('MESSAGE:message.id.empty');
		} else {
			$dialog = $this->_getWindid()->getDialog($dialogId);
			$msg = $this->_getWindid()->delete($this->loginUser->uid, $dialogId, $messageId);
			if ($msg < 1) return $this->showError('WINDID:code.'.$msg);
			$count = $this->_getWindid()->countMessage($dialogId);
			if (!$count) {
				$this->_getNoticesService()->detchDeleteNoticeByType($this->loginUser->uid,'message',array($dialog['from_uid']));
				return $this->showMessage('success','message/message/run');
			}
			}
		return $this->showMessage('success');
	}
	
	/**
	 * 删除对话
	 *
	 * @return void
	 */
	public function deleteDialogAction(Request $request) {
		$ids = $request->get('ids');
		!$ids && return $this->showError('MESSAGE:message.id.empty');
		is_numeric($ids) && $ids = array(intval($ids));
		$dialogs = $this->_getWindid()->fetchDialog($ids);
		$dialog_ids = $from_uids = array();
		foreach ($dialogs as $k=>$v) {
			if ($v['to_uid'] != $this->loginUser->uid) continue;
			$dialog_ids[] = $v['dialog_id'];
			$from_uids[] = $v['from_uid'];
		}
		$msg = $this->_getWindid()->batchDeleteDialog($this->loginUser->uid, $ids);
		if ($msg < 1) return $this->showError('WINDID:code.'.$msg);
		// 这个有点纠结啊
		$this->_getNoticesService()->detchDeleteNoticeByType($this->loginUser->uid,'message',$from_uids);
		return $this->showMessage('success','message/message/run');
	}
	
	/**
	 * 搜索
	 *
	 * @return void
	 */
	public function searchAction(Request $request) {
		list($keyword) = $request->get(array('keyword'));
		empty($keyword) && return $this->showError('MESSAGE:keyword.empty');
		$userinfo = $this->_getUserDs()->getUserByName($keyword);
		if (!$userinfo) {
			return $this->showError('MESSAGE:user.notfound');
		} 
		$dialog = $this->_getWindid()->getDialogByUser($this->loginUser->uid, $userinfo['uid']);
		if (!$dialog) return $this->showError(array('MESSAGE:dialog.notfound',array('{fromUser}' => $keyword)));
		return $this->showMessage('success',url('message/message/dialog', array('dialogid' => $dialog['dialog_id'])));
	}

	/**
	 * 设置
	 *
	 * @return void
	 */
	public function setAction(Request $request) {
		$config = $this->_getMessageDs()->getMessageConfig($this->loginUser->uid);
		$blacklist = $this->_getUserBlack()->getBlacklist($this->loginUser->uid);
		if ($blacklist) {
			$users = $this->_getUserDs()->fetchUserByUid($blacklist);
			foreach ($users as $v) {
				$config['blacklist'][] = $v['username'];
			}
		}
		$noticeValue = $config['notice_types'] ? unserialize($config['notice_types']) : array();
		// notice_types to du
		$config = array (
			'message_tone_Y'  	=> 	$this->loginUser->info['message_tone'] ? 'checked' : '',
			'message_tone_N'  	=> 	$this->loginUser->info['message_tone'] ? '' : 'checked',
			'privacy_N' 		=> $config['privacy'] > 0 ? '' : 'checked',
			'privacy_Y' 		=> $config['privacy'] > 0 ? 'checked' : '',
			'blacklist' 		=> $config['blacklist'] ? $config['blacklist'] : '',
		);
		foreach ($config as $k=>$v) {
			->with($v,$k);
		}
		$noticeTypeSet = $this->_getNoticesService()->getNoticeTypeSet();

		->with($noticeValue,'noticeValue');
		->with($noticeTypeSet,'noticeTypeSet');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:mess.mess.set.title'), '', '');
		Core::setV('seo', $seoBo);
	} 
	
	/**
	 * do设置
	 *
	 * @return void
	 */
	public function doSetAction(Request $request) {
		list($privacy, $message_tone, $notice_types, $blacklist) = $request->get(array('privacy', 'message_tone', 'notice_types', 'blacklist'));
		$noticeTypeSet = $this->_getNoticesService()->getNoticeTypeSet();
		$notice_types = array_diff_key($noticeTypeSet,(array)$notice_types);
		$tmpTypes = array();
		foreach ($notice_types as $k => $v) {
			$tmpTypes[$k] = $k;
		}
		$notice_types = serialize($tmpTypes);

		$userids = array();
		if ($blacklist) {
			$users = $this->_getUserDs()->fetchUserByName($blacklist);
			$userids = array_keys($users);
		}
		
		//只能一个一个存
		$ds = $this->_getUserBlack();
		foreach ($userids AS $uid) {
			$ds->setBlacklist($this->loginUser->uid, $uid);
		}

		$this->_getMessageService()->setMessageConfig($this->loginUser->uid,$privacy,$notice_types,(int)$message_tone);
		return $this->showMessage('success');
	} 
	
	/**
	 * 标记已读
	 *
	 * @return void
	 */
	public function checkReadedAction(Request $request) {
		$ids = $request->get('ids');
		empty($ids) && return $this->showError('MESSAGE:id.empty');
		$result = $this->_getWindid()->readDialog($ids);
		if ($result < 1) return $this->showError('WINDID:code.'.$result);
		return $this->showMessage('success');
	} 
	
	/**
	 * 加入黑名单
	 *
	 * @return void
	 */
	public function addBlackAction(Request $request) {
		$uid = (int)$request->get('uid');
		$username = $request->get('username');
		if ($username) {
			$user = $this->_getUserDs()->getUserByName($username);
			$uid = $user['uid'];
		}
		$uid or return $this->showError('MESSAGE:id.empty');
        app('user.PwUserBlack')->setBlacklist($this->loginUser->uid,$uid);
        //同时取消关注
        app('attention.srv.PwAttentionService')->deleteFollow($this->loginUser->uid, $uid);
        //同时让对方取消关注
        app('attention.srv.PwAttentionService')->deleteFollow($uid, $this->loginUser->uid);
		return $this->showMessage('success');
	} 

	/**
	 * 获取我关注的人
	 *
	 * @return void
	 */
	public function followsAction(Request $request){
		list($page, $perpage, $type) = $request->get(array('page', 'perpage', 'type'));
		$page = $page ? $page : 1;
		$perpage = $perpage ? $perpage : $this->perpage;
		list($start, $limit) = Tool::page2limit($page, $perpage);
		$attentionDs = app('attention.PwAttention');
		$type = $type ? $type : 'follows';
		if ($type == 'follows') {
			$count = $this->loginUser->info['follows'];
			$count && $attentions = $attentionDs->getFollows($this->loginUser->uid, $limit, $start);
		} else {
			$count = $this->loginUser->info['fans'];
			$count && $attentions = $attentionDs->getFans($this->loginUser->uid, $limit, $start);
		}
		if (!$attentions) {
			Tool::echoJson(array('state' => 'fail'));exit;
		}
		Tool::echoJson(array('state' => 'success', 'data' => $this->_buildUsers($attentions)));exit;
	}
	
	/** 
	 * 组装用户
	 *
	 */
	private function _buildUsers($attentions) {
		$uids = array_keys($attentions);
		$userList = $this->_getUserDs()->fetchUserByUid($uids, PwUser::FETCH_MAIN);
		$users = array();
		foreach ($uids as $v) {
			if (!isset($userList[$v]['username'])) continue;
			$users[$v]['uid'] = $v;
			$users[$v]['username'] = $userList[$v]['username'];
		}	
		return $users;
	}

	/** 
	 * 获得验证码
	 */
	public function showverifyAction(Request $request) {
		$veryfy = new PwVerifyCode();
		$veryfy->showVerifyCode();
	}
	
	/**
	 * 更新用户表未读消息计数
	 *
	 * @param int $uid
	 * @param int $num
	 * @return void
	 */
	private function _updateMessageCount($uid,$num) {
		//更新用户表未读数
		Wind::import('SRV:user.dm.PwUserInfoDm');
		$user = app('user.PwUser');
		$dm = new PwUserInfoDm($uid);
		$dm->addMessages($num);
		$user->editUser($dm, PwUser::FETCH_DATA);
	}
	
	private function _parseEmotion($message) {
		Wind::import('LIB:ubb.PwUbbCode');
		$message = $this->_parseUrl($message);
		$message = Tool::substrs($message, 36);
		return PwUbbCode::parseEmotion($message);
	}
	
	private function _parseUrl($message) {
		$searcharray = array(
			"/\[url=((https?|ftp|gopher|news|telnet|mms|rtsp|thunder|ed2k)?[^\[\s]+?)(\,(1)\/?)?\](.+?)\[\/url\]/eis",
			"/\[url\]((https?|ftp|gopher|news|telnet|mms|rtsp|thunder|ed2k)?[^\[\s]+?)\[\/url\]/eis"
		);
		preg_match("/\[url\]((https?|ftp|gopher|news|telnet|mms|rtsp|thunder|ed2k)?[^\[\s]+?)\[\/url\]/eis", $message, $match);
		return $match[1] ? $match[1] : $message;
	}
	/**
	 * 
	 * @return PwUser
	 */
	private function _getUserDs(){
		return app('user.PwUser');
	}
	
	/**
	 * 
	 * @return PwMessageService
	 */
	private function _getMessageService() {
		return app('message.srv.PwMessageService');
	}
	
	/**
	 * 
	 * @return PwMessageMessages
	 */
	private function _getMessageDs() {
		return app('message.PwMessageMessages');
	}
	
	private function _getWindid() {
		return WindidApi::api('message');
	}
	
	private function _getWindidUser() {
		return app(UserApi::class);
	}
	
	/**
	 * PwUserBehavior
	 * 
	 * @return PwUserBehavior
	 */
	private function _getUserBehaviorDs() {
		return app('user.PwUserBehavior');
	}
	
	/**
	 * PwNoticeService
	 * 
	 * @return PwNoticeService
	 */
	private function _getNoticesService(){
		return app('message.srv.PwNoticeService');
	}
	
	/** 
	 * 获得PwUserBlack DS
	 *
	 * @return PwUserBlack
	 */
	private function _getUserBlack() {
		return app('user.PwUserBlack');
	}
	
	/** 
	 * PwCheckVerifyService
	 *
	 * @return PwCheckVerifyService
	 */
	private function _getVerifyService() {
		return app("verify.srv.PwCheckVerifyService");
	}
}
