<?php
/**
 * 用户注册过滤
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: UserRegisterFilter.php 18671 2012-09-26 02:49:27Z xiaoxia.xuxx $
 * @package 
 */
class UserRegisterFilter extends WindActionFilter {

	/* (non-PHPdoc)
	 * @see WindHandlerInterceptor::preHandle()
	 */
	public function preHandle() {
		/* @var $userBo PwUserBo */
		$userBo = Core::getLoginUser();
		if ($userBo->isExists() && !in_array($this->router->getAction(), array('welcome', 'guide'))) {
			
			//TODO 好友邀请链接
			$inviteCode = $request->get('invite');
			if ($inviteCode) {
				$user = app('SRV:invite.srv.PwInviteFriendService')->invite($inviteCode, $userBo->uid);
				if ($user instanceof ErrorBag) {
					return $this->showError($user->getError());
				}
			}
			if (strtolower($this->router->getAction()) == strtolower('activeEmail')) {
				$referer = Core::C('site', 'info.url');
			} else {
				$referer = $request->getServer('HTTP_REFERER');
			}
			$this->errorMessage->addError($referer ? $referer : url(''), 'referer');
			$this->errorMessage->addError(2, 'refresh');
			$this->errorMessage->sendError('USER:register.dumplicate');
		}
	}

	/* (non-PHPdoc)
	 * @see WindHandlerInterceptor::postHandle()
	 */
	public function postHandle() {
	}
}