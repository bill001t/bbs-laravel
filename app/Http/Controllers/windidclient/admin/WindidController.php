<?php
Wind::import('ADMIN:library.AdminBaseController');

/**
 * the last known user to change this file in the repository  <$LastChangedBy: jieyin $>
 * @author $Author: jieyin $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: WindidController.php 28890 2013-05-29 06:23:04Z jieyin $ 
 * @package 
 */
class WindidController extends AdminBaseController {
	
	public function run() {
		$config = Core::C()->getValues('windid');
		->with($config, 'config');
	}
	
	public function dorunAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		list($windid, $serverUrl, $clientId, $clientKey, $connect) = $request->get(array('windid', 'serverUrl', 'clientId', 'clientKey', 'connect'), 'post');

		if ($windid == 'local') {
			$serverUrl = Core::C('site', 'info.url'). '/windid';
			$clientId = 1;
			!$clientKey && $clientKey = md5(Utility::generateRandStr(10));
			$connect = 'db';
		}

		$config = new PwConfigSet('windid');
		$config->set('windid', $windid)
			->set('serverUrl', $serverUrl)
			->set('clientId', $clientId)
			->set('clientKey', $clientKey)
			->set('connect', $connect);

		if ($windid == 'client') {
			list($dbhost, $dbport, $dbuser, $dbpwd, $dbname, $dbprefix, $dbcharset) = $request->get(array('dbhost', 'dbport', 'dbuser', 'dbpwd', 'dbname', 'dbprefix', 'dbcharset'), 'post');
			$config->set('db.host', $dbhost)
				->set('db.port', $dbport)
				->set('db.user', $dbuser)
				->set('db.pwd', $dbpwd)
				->set('db.name', $dbname)
				->set('db.prefix', $dbprefix)
				->set('db.charset', $dbcharset);
		}
		$config->flush();
		
		if ($clientId) {
			Core::C()->reload('windid');
			$service = WindidApi::api('app');
			WindidApi::getDm('app');

			if (!$service->getApp($clientId)) {
				$charset = Core::V('charset');
				$charset = str_replace('-', '', strtolower($charset));
				if (!in_array($charset, array('gbk', 'utf8', 'big5'))) $charset = 'utf8';

				$dm = new WindidAppDm();
				$dm->setId($clientId)
					->setApiFile('windid.php')
					->setIsNotify(1)
					->setIsSyn(1)
					->setAppName(Core::C('site', 'info.name'))
					->setSecretkey($clientKey)
					->setAppUrl(Core::C('site', 'info.url'))
					->setAppIp(Wind::getComponent('request')->getClientIp())
					->setCharset($charset);
				$service->addApp($dm);
			} elseif ($clientKey) {
				$dm = new WindidAppDm($clientId);
				$dm->setSecretkey($clientKey)
					->setAppUrl(Core::C('site', 'info.url'))
					->setCharset($charset);
				$service->editApp($dm);
			}
        }

        $avatarUrl = app(AvatarApi::class)->getAvatarUrl();
        if ($avatarUrl != WindidError::SERVER_ERROR) {
            Core::C()->setConfig('site', 'avatarUrl', $avatarUrl);
            return $this->showMessage('ADMIN:success');
        }

		return $this->showError('ADMIN:avatarapi.call.fail');
	}
	
	private function _getWindid() {
		return WindidApi::api('config');
	}
}
?>
