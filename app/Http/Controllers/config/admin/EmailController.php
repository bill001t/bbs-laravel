<?php
Wind::import('ADMIN:library.AdminBaseController');

/**
 * 后台设置-站点设置-站点信息设置/全局参数设置
 *
 * @author Qiong Wu <papa0924@gmail.com> 2011-12-7
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: EmailController.php 3286 2011-12-15 09:32:42Z yishuo $
 * @package admin
 * @subpackage controller.config
 */
class EmailController extends AdminBaseController {

	/* (non-PHPdoc)
	 * @see WindController::run()
	 */
	public function run() {
		$config = $this->_getConfig();
		->with($config, 'config');
		$t = Tool::strlen($config['mail.password']);
		$password = Tool::substrs($config['mail.password'], 1, 0, false) . '********' . Tool::substrs($config['mail.password'], 1, $t-1, false);
		->with($password, 'password');
	}

	/**
	 * 后台设置-email设置
	 */
	public function dorunAction(Request $request) {
		$request->isPost() || return $this->showError('operate.fail');

		$password = $request->get('mailPassword', 'post');
		$config = $this->_getConfig();
		$t = Tool::strlen($config['mail.password']);
		$passwordO = Tool::substrs($config['mail.password'], 1, 0, false) . '********' . Tool::substrs($config['mail.password'], 1, $t-1, false);
		$password = $password == $passwordO ? $config['mail.password'] : $password;
		$config = new PwConfigSet('email');
		$config->set('mailOpen', $request->get('mailOpen', 'post'))
			->set('mailMethod', 'smtp')
			->set('mail.host', $request->get('mailHost', 'post'))
			->set('mail.port', $request->get('mailPort', 'post'))
			->set('mail.from', $request->get('mailFrom', 'post'))
			->set('mail.auth', $request->get('mailAuth', 'post'))
			->set('mail.user', $request->get('mailUser', 'post'))
			->set('mail.password', $password)
			->flush();
		return $this->showMessage('ADMIN:success');
	}
	
	/**
	 * 发送测试邮件
	 */
	public function sendAction(Request $request) {
		$config = $this->_getConfig();
		->with($config['mail.from'], 'from');
	}
	
	/**
	 * 发送测试邮件
	 */
	public function dosendAction(Request $request) {
		Wind::import('LIB:utility.PwMail');
		list($fromEmail, $toEmail) = $request->get(array('fromEmail', 'toEmail'), 'post');
		if (!$toEmail) return $this->showError('ADMIN:email.test.toemail.require');
		$mail = new PwMail();
		$title = Core::C('site', 'info.name') . ' 测试邮件';
		$content = '恭喜您，如果您收到此邮件则代表后台邮件发送设置正确！';
		$result = $mail->sendMail($toEmail, $title, $content);
		if ($result === true) {
			return $this->showMessage('ADMIN:email.test.success');
		}
		$i18n = Wind::getComponent('i18n');
		return $this->showError(array('ADMIN:email.test.error', array('{error}' => $i18n->getMessage($result->getError()))));
	}
	
	/**
	 * 加载Config DS 服务
	 * 
	 * @return array
	 */
	private function _getConfig() {
		return Core::C()->getValues('email');
	}
}
