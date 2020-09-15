<?php

namespace App\Core;

use Cache;

class PwCache
{
	public $keys = array();
	protected $_prekeys = array();
	protected $_readykeys = array();
	protected $_cacheData = array();

	public function mergeKeys($keys) {
		$this->keys = array_merge($this->keys, $keys);
	}

	public function preset($keys) {
		foreach ($keys as $key) {
			$this->_prekeys[] = $key;
		}
	}

	public function bulidKey($key, $param = array()) {
		if (!isset($this->keys[$key])) return $key;
		$vkey = $this->keys[$key][0];
		if ($param) {
			array_unshift($param, $vkey);
			$vkey = call_user_func_array('sprintf', $param);
		}
		return $vkey;
	}

	public function bulidKeys($keys) {
		$vkeys = array();
		foreach ($keys as $key => $value) {
			$vkeys[$key] = is_array($value) ? $this->bulidKey($value[0], $value[1]) : $this->bulidKey($value);
		}
		return $vkeys;
	}

	public function get($key, $param = array()) {
		is_array($param) || $param = array($param);
		$vkey = $this->bulidKey($key, $param);
		if (!isset($this->_cacheData[$vkey]) && ($this->_cacheData[$vkey] = Cache::get($key)) === null) {
			$this->_readykeys[$vkey] = array($key, $param);
			$this->_query();
		}
		return $this->_cacheData[$vkey];
	}

	public function fetch($keys) {
		$vkeys = $this->bulidKeys($keys);

		foreach ($vkeys as $i => $vkey) {
			if (!isset($this->_cacheData[$vkey])) {
				$value = is_array($keys[$i]) ? $keys[$i] : array($keys[$i], array());
				$this->_readykeys[$vkey] = $value;
			}
		}

        $this->_query();

        return Tool::subArray($this->_cacheData, $vkeys);
	}

	public function set($key, $value, $param = array(), $expires = 0) {
		$vkey = $this->bulidKey($key, $param);
		$expires = isset($this->keys[$key]) ? $this->keys[$key][4] : $expires;
		return Cache::put($vkey, $value, $expires);
	}

	public function delete($key, $param = array()) {
		$vkey = $this->bulidKey($key, $param);
		return Cache::forget($vkey);
	}

	public function batchDelete($keys) {
		$vkeys = $this->bulidKeys($keys);
		foreach ($vkeys as $i => $vkey) {
			Cache::forget($vkey);
		}
		return true;
	}

	public function increment($key, $param = array(), $step = 1) {
		$vkey = $this->bulidKey($key, $param);
		return Cache::increment($vkey, $step);
	}

	protected function _prepare() {
		foreach ($this->_prekeys as $value) {
			if (!is_array($value)) {
				$value = array($value, array());
			}
			list($key, $param) = $value;
			$vkey = $this->bulidKey($key, $param);
			$this->_readykeys[$vkey] = $value;
		}
		$this->_prekeys = array();
	}
	
	protected function _query() {
		$result = [];
		$this->_prepare();

        $vkeys = array_keys($this->_readykeys);
        foreach($vkeys as $vkey){
            $value = Cache::get($vkey);
            if ($value !== null) continue;

            list($key, $param) = $this->_readykeys[$vkey];
            if (!isset($this->keys[$key]) || !isset($this->keys[$key][3])) continue;
            if (is_array($this->keys[$key][3])) {
                list($srv, $method) = $this->keys[$key][3];

                $result[$vkey] = call_user_func_array([app($srv), $method], $param);
            } else {
                $result[$vkey] = $this->keys[$key][3];
            }

            Cache::put($key, $result[$vkey], $this->keys[$key][2]);
        }

        $this->_cacheData = array_merge($this->_cacheData, $result);
        $this->_readykeys = array();
	}
}
