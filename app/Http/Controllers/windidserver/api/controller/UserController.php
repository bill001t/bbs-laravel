<?php
Wind::import('APPS:api.controller.OpenBaseController');

/**
 * windid用户接口
 * the last known user to change this file in the repository  <$LastChangedBy: jieyin $>
 * @author $Author: jieyin $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: UserController.php 24768 2013-02-20 11:03:35Z jieyin $ 
 * @package 
 */
class UserController extends OpenBaseController {
	
	public function loginAction(Request $request) {
		list($userid, $password, $type, $ifcheck, $question, $answer) = $request->get(array('userid', 'password', 'type', 'ifcheck', 'question', 'answer'), 'post');
		!$type && $type = 2;
		$ifcheck = (bool)$ifcheck;
		$result = $this->_getUserService()->login($userid, $password, $type, $ifcheck, $question, $answer);
		$this->output($result);
	}

	public function synLoginAction(Request $request) {
		$uid = (int)$request->get('uid', 'post');
		$out = '';
		$result = $this->_getNotifyService()->syn('synLogin', $uid, $this->appid);
		foreach ($result AS $val) {
			$out .= '<script type="text/javascript" src="' . $val . '"></script>';
		}
		$this->output($out);
	}
	
	public function synLogoutAction(Request $request) {
		$uid = (int)$request->get('uid', 'post');
		$out = '';
		$result = $this->_getNotifyService()->syn('synLogout', $uid, $this->appid);
		foreach ($result AS $val) {
			$out .= '<script type="text/javascript" src="' . $val . '"></script>';
		}
		$this->output($out);
	}
	
	public function checkInputAction(Request $request) {
		list($input, $type, $username, $uid) = $request->get(array('input', 'type', 'username', 'uid'), 'post');
		$result = $this->_getUserService()->checkUserInput($input, $type, $username, $uid);
		$this->output(WindidUtility::result($result));
	}
	
	public function checkQuestionAction(Request $request) {
		list($question, $answer, $uid) = $request->get(array('question', 'answer', 'uid'), 'post');
		$result = $this->_getUserService()->checkQuestion($uid, $question, $answer);
		$this->output(WindidUtility::result($result));
	}
	
	public function getAction(Request $request) {
		list($userid, $type, $fetch) = $request->get(array('userid', 'type', 'fetch'), 'get');
		!$type && $type = 1;
		!$fetch && $fetch = 1;
		$result = $this->_getUserService()->getUser($userid, $type, $fetch);
		$this->output($result);
	}
	
	/**
	 * 批量获取用户信息
	 */
	public function fecthAction(Request $request) {
		list($userids, $type, $fetch) = $request->get(array('userids', 'type', 'fetch'), 'get');
		!$type && $type = 1;
		!$fetch && $fetch = 1;
		$result = $this->_getUserService()->fecthUser($userids, $type, $fetch);
		$this->output($result);
	}
	
	/**
	 * 增加一个用户
	 */
	public function addUserAction(Request $request) {
		list(
			$username, $password, $email, $question, $answer, $regip, $realname, $profile, $regdate, $gender,
			$byear, $bmonth, $bday, $hometown, $location, $homepage, $qq, $msn, $aliww, $mobile, $alipay, $messages
		) = $request->get(array(
			'username', 'password', 'email', 'question', 'answer', 'regip', 'realname', 'profile', 'regdate', 'gender',
			'byear', 'bmonth', 'bday', 'hometown', 'location', 'homepage', 'qq', 'msn', 'aliww', 'mobile', 'alipay', 'messages'
		), 'post');
		
		Wind::import('WSRV:user.dm.WindidUserDm');
		$dm = new WindidUserDm();
		$dm->setUsername($username);
		$dm->setPassword($password);
		$dm->setEmail($email);

		isset($question) && $dm->setQuestion($question);
		isset($answer) && $dm->setAnswer($answer);
		isset($regip) && $dm->setRegip($regip);
		isset($realname) && $dm->setRealname($realname);
		isset($profile) && $dm->setProfile($profile);
		isset($regdate) && $dm->setRegdate($regdate);

		isset($gender) && $dm->setGender($gender);
		isset($byear) && $dm->setByear($byear);
		isset($bmonth) && $dm->setBmonth($bmonth);
		isset($bday) && $dm->setBday($bday);
		isset($hometown) && $dm->setHometown($hometown);
		isset($location) && $dm->setLocation($location);
		isset($homepage) && $dm->setHomepage($homepage);
		isset($qq) && $dm->setQq($qq);
		isset($msn) && $dm->setMsn($msn);
		isset($aliww) && $dm->setAliww($aliww);
		isset($mobile) && $dm->setMobile($mobile);
		isset($alipay) && $dm->setAlipay($alipay);
		isset($messages) && $dm->setMessageCount($messages);
		
		$result = $this->_getUserDs()->addUser($dm);
		if ($result instanceof WindidError) {
			$this->output($result->getCode());
		}

		$uid = (int)$result;
		$this->_getUserService()->defaultAvatar($uid, 'face');
		$this->_getNotifyService()->send('addUser', array('uid' => $uid), $this->appid);
		$this->output($uid);
	}
	
	/**
	 * 修改用户信息
	 */
	public function editUserAction(Request $request) {
		list(
			$uid, $username, $password, $old_password, $email, $question, $answer, $regip, $realname, $profile, $regdate,
			$gender, $byear, $bmonth, $bday, $hometown, $location, $homepage, $qq, $msn, $aliww, $mobile, $alipay,
			$addmessages, $messages
		) = $request->get(array(
			'uid', 'username', 'password', 'old_password', 'email', 'question', 'answer', 'regip', 'realname', 'profile', 'regdate', 
			'gender', 'byear', 'bmonth', 'bday', 'hometown', 'location', 'homepage', 'qq', 'msn', 'aliww', 'mobile', 'alipay',
			'addmessages', 'messages'
		), 'post');

		Wind::import('WSRV:user.dm.WindidUserDm');
		$dm = new WindidUserDm($uid);
		isset($username) && $dm->setUsername($username);
		isset($password) && $dm->setPassword($password);
		isset($old_password) && $dm->setOldpwd($old_password);
		isset($email) && $dm->setEmail($email);
		isset($question) && $dm->setQuestion($question);
		isset($answer) && $dm->setAnswer($answer);
		isset($regip) && $dm->setRegip($regip);
		isset($realname) && $dm->setRealname($realname);
		isset($profile) && $dm->setProfile($profile);
		isset($regdate) && $dm->setRegdate($regdate);

		isset($gender) && $dm->setGender($gender);
		isset($byear) && $dm->setByear($byear);
		isset($bmonth) && $dm->setBmonth($bmonth);
		isset($bday) && $dm->setBday($bday);
		isset($hometown) && $dm->setHometown($hometown);
		isset($location) && $dm->setLocation($location);
		isset($homepage) && $dm->setHomepage($homepage);
		isset($qq) && $dm->setQq($qq);
		isset($msn) && $dm->setMsn($msn);
		isset($aliww) && $dm->setAliww($aliww);
		isset($mobile) && $dm->setMobile($mobile);
		isset($alipay) && $dm->setAlipay($alipay);

		isset($addmessages) && $dm->addMessages($addmessages);
		isset($messages) && $dm->setMessageCount($messages);
		
		$result = $this->_getUserDs()->editUser($dm);
		if ($result instanceof WindidError) {
			$this->output($result->getCode());
		}
		$this->_getNotifyService()->send('editUser', array('uid' => $uid, 'changepwd' => $dm->password ? 1 : 0), $this->appid);
		$this->output(WindidUtility::result(true));
	}
	
	/**
	 * 删除一个用户
	 */
	public function deleteAction(Request $request) {
		$uid = $request->get('uid', 'post');
		$result = false;
		if ($this->_getUserDs()->deleteUser($uid)) {
			$this->_getNotifyService()->send('deleteUser', array('uid' => $uid), $this->appid);
			$result = true;
		}
		$this->output(WindidUtility::result($result));
	}
	
	/**
	 * 删除多个用户
	 */
	public function batchDeleteAction(Request $request) {
		$uids = $request->get('uids', 'post');
		$result = false;
		if ($this->_getUserDs()->batchDeleteUser($uids)) {
			foreach ($uids as $uid) {
				$this->_getNotifyService()->send('deleteUser', array('uid' => $uid), $this->appid);
			}
			$result = true;
		}
		$this->output(WindidUtility::result($result));
	}

	/**
	 * 获取用户积分
	 *
	 * @param int $uid
	 */
	public function getCreditAction(Request $request) {
		$result = $this->_getUserService()->getUserCredit($request->get('uid', 'get'));
		$this->output($result);
	}
	
	/**
	 * 批量获取用户积分
	 *
	 * @param array $uids
	 * @return array
	 */
	public function fecthCreditAction(Request $request) {
		$uids = $request->get('uids', 'get');
		$result = $this->_getUserService()->fecthUserCredit($uids);
		$this->output($result);
	}
	
	/**
	 * 更新用户积分
	 *
	 * @param int $uid
	 * @param int $cType (1-8)
	 * @param int $value
	 */
	public function editCreditAction(Request $request) {
		$uid = (int)$request->get('uid', 'post');
		$cType = (int)$request->get('cType', 'post');
		$value = (int)$request->get('value', 'post');
		$isset = (bool)$request->get('isset', 'post');

		$result = $this->_getUserService()->editCredit($uid, $cType, $value, $isset);
		if ($result instanceof WindidError) {
			$this->output($result->getCode());
		}
		if ($result) {
			$this->_getNotifyService()->send('editCredit', array('uid' => $uid), $this->appid);
		}
		$this->output(WindidUtility::result($result));
	}
	
	public function editDmCreditAction(Request $request) {
		$uid = (int)$request->get('uid', 'post');
		list($set, $add) = $request->get(array('set', 'add'), 'post');

		Wind::import('WSRV:user.dm.WindidCreditDm');
		$dm = new WindidCreditDm($uid);
		if ($set && is_array($set)) {
			foreach ($set as $key => $value) {
				$dm->setCredit($key, $value);
			}
		}
		if ($add && is_array($add)) {
			foreach ($add as $key => $value) {
				$dm->addCredit($key, $value);
			}
		}
		$result = $this->_getUserDs()->updateCredit($dm);
		if ($result instanceof WindidError) {
			$this->output($result->getCode());
		}
		if ($result) {
			$this->_getNotifyService()->send('editCredit', array('uid' => $uid), $this->appid);
		}
		$this->output(WindidUtility::result($result));
	}

	/**
	 * 清空一个积分字段
	 *
	 * @param int $num >8
	 */
	public function clearCreditAction(Request $request) {
		$result = $this->_getUserDs()->clearCredit($request->get('num', 'post'));
		$this->output(WindidUtility::result($result));
	}
	
	/**
	 * 获取用户黑名单
	 *
	 * @param int $uid
	 * @return array uids
	 */
	public function getBlackAction(Request $request) {
		$result = $this->_getUserBlackDs()->getBlacklist($request->get('uid', 'get'));
		$this->output($result);
	}
	
	public function fetchBlackAction(Request $request) {
		$uids = $request->get('uids', 'get');
		$result = $this->_getUserBlackDs()->fetchBlacklist($uids);
		$this->output($result);
	}
	
	/**
	 * 增加黑名单
	 *
	 * @param int $uid
	 * @param int $blackUid
	 */
	public function addBlackAction(Request $request) {
		$result = $this->_getUserBlackDs()->addBlackUser($request->get('uid', 'post'), $request->get('blackUid', 'post'));
		$this->output(WindidUtility::result($result));
	}
	
	public function replaceBlackAction(Request $request) {
		$uid = $request->get('uid', 'post');
		$blackList = $request->get('blackList', 'post');
		$result = $this->_getUserBlackDs()->setBlacklist($uid, $blackList);
		$this->output(WindidUtility::result($result));
	}
	
	/**
	 * 删除某的黑名单 $blackUid为空删除所有
	 *
	 * @param int $uid
	 * @param int $blackUid
	 */
	public function delBlackAction(Request $request) {
		$result = $this->_getUserService()->delBlack($request->get('uid', 'post'), $request->get('blackUid', 'post'));
		$this->output(WindidUtility::result($result));
	}
	
	protected function _getUserDs() {
		return app('WSRV:user.WindidUser');
	}
	
	protected function _getUserService() {
		return app('WSRV:user.srv.WindidUserService');
	}
	
	private function _getNotifyService() {
		return app('WSRV:notify.srv.WindidNotifyService');
	}
	
	protected function _getUserBlackDs() {
		return app('WSRV:user.WindidUserBlack');
	}
}
?>
