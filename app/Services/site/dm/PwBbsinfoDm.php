<?php

namespace App\Services\site\dm;

use App\Core\BaseDm;

class PwBbsinfoDm extends BaseDm {
	
	public $id;

	public function __construct($id = 1) {
		$this->id = $id;
	}

	public function setNewmember($newmember) {
		$this->_data['newmember'] = $newmember;
		return $this;
	}
	
	public function setTotalmember($totalmember) {
		$this->_data['totalmember'] = intval($totalmember);
		return $this;
	}

	public function addTotalmember($num) {
		$this->_increaseData['totalmember'] = intval($num);
		return $this;
	}

	public function setHigholnum($higholnum) {
		$this->_data['higholnum'] = intval($higholnum);
		return $this;
	}

	public function setHigholtime($higholtime) {
		$this->_data['higholtime'] = intval($higholtime);
		return $this;
	}

	public function setYposts($yposts) {
		$this->_data['yposts'] = $yposts;
		return $this;
	}
	
	public function setHposts($hposts) {
		$this->_data['hposts'] = $hposts;
		return $this;
	}

	protected function _beforeAdd() {
		return true;
	}

	protected function _beforeUpdate() {
		return true;
	}
}
?>