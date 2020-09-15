<?php

namespace App\Services\credit\vo;

class PwCreditLogSc {
	
	protected $_data = array();

	public function getData() {
		return $this->_data;
	}

	public function hasData() {
		return !empty($this->_data);
	}
	
	/**
	 * 搜索帖子标题
	 */
	public function setCtype($ctype) {
		$this->_data['ctype'] = $ctype;
		return $this;
	}
	
	/**
	 * 搜索作者
	 */
	public function setUserid($uid) {
		$this->_data['created_userid'] = $uid;
		return $this;
	}
	
	/**
	 * 发帖时间区间，起始
	 */
	public function setCreateTimeStart($time) {
		$this->_data['created_time_start'] = $time;
		return $this;
	}
	
	/**
	 * 发帖时间区间，结束
	 */
	public function setCreateTimeEnd($time) {
		$this->_data['created_time_end'] = $time + 86400;
		return $this;
	}
	
	public function setAward($award) {
		$this->_data['award'] = intval($award);
	}
}