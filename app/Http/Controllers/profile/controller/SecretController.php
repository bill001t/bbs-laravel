<?php
Wind::import('APPS:.profile.controller.BaseProfileController');

/**
 * 隐私设置
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: SecretController.php 23994 2013-01-18 03:51:46Z long.shi $
 * @package src.products.u.controller.profile
 */
class SecretController extends BaseProfileController {

	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		$this->setCurrentLeft();
		$model = $this->getProfileMenu();
		unset($model['profile'], $model['contact'], $model['tag']);
		$userInfo = app('user.PwUser')->getUserByUid($this->loginUser->uid, PwUser::FETCH_INFO);
		$secret = $userInfo['secret'] ? unserialize($userInfo['secret']) : array();
		//手机号码默认仅自己可见
		!isset($secret['mobile']) && $secret['mobile'] = 1;
		->with($model, 'model');
		->with($secret, 'secret');
		->with($this->getSecretOption(), 'option');
		$this->appendBread('空间隐私', 'profile/secret/run');
		return view('profile_secret');
		
		// seo设置
		Wind::import('SRV:seo.bo.PwSeoBo');
		$seoBo = PwSeoBo::getInstance();
		$lang = Wind::getComponent('i18n');
		$seoBo->setCustomSeo($lang->getMessage('SEO:profile.secret.run.title'), '', '');
		Core::setV('seo', $seoBo);
	}
	
	public function dorunAction(Request $request) {
		$_array = array();
		$model = $this->getProfileMenu();
		unset($model['profile'], $model['contact'], $model['tag']);
		if (count($model) > 1){
			$post = array_keys($model);
		}
		$array = array('space', 'constellation', 'local', 'nation', 'aliwangwang', 'qq','msn', 'mobile');
		$array = array_merge($array,$post);
		foreach ($array AS $value) {
			$_array[$value] = (int)$request->get($value,'post');
		}
		Wind::import('SRV:user.dm.PwUserInfoDm');
		$dm = new PwUserInfoDm($this->loginUser->uid);
		$dm->setSecret($_array);
		$resource = app('user.PwUser')->editUser($dm, PwUser::FETCH_INFO);
		if ($resource instanceof ErrorBag) return $this->showError($resource->getError());
		return $this->showMessage("MEDAL:success");
	}
	
	/**
	 * 设置黑名单
	 */
	public function blackAction(Request $request) {
		$this->setCurrentLeft();
		$blacklist = app('user.PwUserBlack')->getBlacklist($this->loginUser->uid);
		$blacks = array();
		if ($blacklist) {
			$users = app('user.PwUser')->fetchUserByUid($blacklist);
			foreach ($users as $v) {
				$blacks[] = $v['username'];
			}
		}
		->with($blacks,'blacklist');
		$this->appendBread('黑名单', 'profile/secret/black');
		return view('profile_black');
	}
	
	/**
	 * do设置黑名单
	 */
	public function doblackAction(Request $request) {
		$blacklist = $request->get('blacklist');
		$userids = array();
		if ($blacklist) {
			$users = app('user.PwUser')->fetchUserByName($blacklist);
			$userids = array_keys($users);
		}
		($blacklist && !$userids) && return $this->showError('USER:profile.secret.username.error');
		if (count($userids) > 50) return $this->showError('USER:profile.secret.username.num.error');
		//只能一个一个存
		$ds = app('user.PwUserBlack');
		$ds->replaceBlack($this->loginUser->uid, $userids);
		$attentionService = app('attention.srv.PwAttentionService');
		foreach ($userids as $uid) {
			$attentionService->deleteFollow($this->loginUser->uid, $uid);
			$attentionService->deleteFollow($uid, $this->loginUser->uid);
		}
		return $this->showMessage('success');
	}
	
	protected function getSecretOption() {
		$lang = Wind::getComponent('i18n');
		return array(
			0 => $lang->getMessage('USER:secret.option.open'),
			1 => $lang->getMessage('USER:secret.option.myself'),
			2 => $lang->getMessage('USER:secret.option.attention')
		);
	}
	
	/**
	 * 获得个人设置的菜单
	 */
	private function getProfileMenu() {
		return app('APPS:profile.service.PwUserProfileMenu')->getTabs('profile');
	}
}