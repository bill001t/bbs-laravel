<?php

namespace App\Services\hook\bm;

use App\Services\hook\bs\PwHookInject;

class PwHookInjectService {

	/**
	 * 根据HookName获取
	 * 
	 * 返回格式：
	 *	'alias1' => array(
	 *		'class' => '', 
	 *		'method' => '', 
	 *		'expression' => '',
	 *		'loadway' => '',
	 *	),
	 * 'alias2' => array(
	 *		'class' => '',
	 *	),
	 *	'alias3' => array(
	 *		'class' => ''
	 *	),
	 *	'alias4' => array(
	 *		'class' => '',
	 *		'method' => '',
	 *		'loadway' => ''
	 *	),
	 * @param string $hookName
	 * @return array
	 */
	public function getInjectByHookName($hookName) {
		$_r = $this->_loadHookInjectDs()->findByHookName($hookName);
		$_result = array();
		foreach ($_r as $key => $value) {
			$_result[$value['alias']] = array(
				'class' => eval($value['class']),
				'method' => $value['method'], 
				'loadway' => $value['loadway'], 
				'expression' => $value['expression']
			);
		}
		return $_result;
	}

	public function fetchInjectByHookName($hookNames) {
		$_r = $this->_loadHookInjectDs()->fetchByHookName($hookNames);
		$_result = array();
		foreach ($hookNames as $key) {
			$_result[$key] = array();
		}
		foreach ($_r as $key => $value) {
			$_result[$value['hook_name']][$value['alias']] = array(
				'class' => $value['class'], 
				'method' => $value['method'], 
				'loadway' => $value['loadway'], 
				'expression' => $value['expression']
			);
		}
		return $_result;
	}

	/**
	 * @return PwHookInject
	 */
	private function _loadHookInjectDs() {
		return app(PwHookInject::class);
	}
}

?>