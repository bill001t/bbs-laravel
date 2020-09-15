<?php
Wind::import('APPS:api.controller.OpenBaseController');

/**
 * the last known user to change this file in the repository  <$LastChangedBy: jieyin $>
 * @author $Author: jieyin $ Foxsee@aliyun.com
 * @copyright ?2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: ConfigController.php 24719 2013-02-17 06:50:42Z jieyin $ 
 * @package 
 */

class ConfigController extends OpenBaseController{
	
	public function getAction(Request $request) {
		$name = $request->get('name', 'get');
		$key = '';
		if (strpos($name, ':') !== false) {
			list($namespace, $key) = explode(':', $name);
		} else {
			$namespace = $name;
		}
		$config = $this->_getConfigDs()->getValues($namespace);
		$result = $key ? $config[$key] : $config;
		$this->output($result);
	}
	
	public function getConfigAction(Request $request) {
		$namespace = $request->get('namespace', 'get');
		$result = $this->_getConfigDs()->getConfig($namespace);
		$this->output($result);
	}

	public function fetchConfigAction(Request $request) {
		$namespace = $request->get('namespace', 'get');
		$result = $this->_getConfigDs()->fetchConfig($namespace);
		$this->output($result);
	}

	public function getConfigByNameAction($namespace, $name) {
		list($namespace, $name) = $request->get(array('namespace', 'name'), 'get');
		$result = $this->_getConfigDs()->getConfigByName($namespace, $name);
		$this->output($result);
	}

	public function getValuesAction(Request $request) {
		$namespace = $request->get('namespace', 'get');
		$result = $this->_getConfigDs()->getValues($namespace);
		$this->output($result);
	}
	
	public function setConfigAction(Request $request) {
		list($namespace, $key, $value) = $request->get(array('namespace', 'key', 'value'), 'post');
		$result = $this->_getConfigDs()->setConfig($namespace, $key, $value);
		$this->output(WindidUtility::result(true));
	}

	public function setConfigsAction(Request $request) {
		list($namespace, $data) = $request->get(array('namespace', 'data'), 'post');
		$result = $this->_getConfigDs()->setConfigs($namespace, $data);
		$this->output(WindidUtility::result(true));
	}
	
	public function deleteConfigAction(Request $request) {
		$namespace = $request->get('namespace', 'post');
		$result = $this->_getConfigDs()->deleteConfig($namespace);
		$this->output(WindidUtility::result(true));
	}

	public function deleteConfigByNameAction(Request $request) {
		list($namespace, $name) = $request->get(array('namespace', 'name'), 'post');
		$result = $this->_getConfigDs()->deleteConfigByName($namespace, $name);
		$this->output(WindidUtility::result(true));
	}

	public function setCreditsAction(Request $request) {
		$credits = $request->get('credits', 'post');
		$this->_getConfigService()->setLocalCredits($credits);
		$this->_getNotifyService()->send('setCredits', array(), $this->appid);
		$this->output(WindidUtility::result(true));
	}

	protected function _getConfigDs() {
		return app('WSRV:config.WindidConfig');
	}

	private function _getConfigService() {
		return app('WSRV:config.srv.WindidCreditSetService');
	}

	private function _getNotifyService() {
		return app('WSRV:notify.srv.WindidNotifyService');
	}
}
?>