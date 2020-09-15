<?php

namespace App\Services\credit\dm;

use App\Core\BaseDm;

class PwCreditDm extends BaseDm
{

    public $dm = null;
    public $uid;
    protected $_data = array();
    protected $_increaseData = array();

    public function __construct($uid)
    {
        $this->uid = $uid;
    }

    public function getData()
    {
        return $this->_data;
    }

    public function addCredit($cType, $value)
    {
        if (!$this->_isLegal($cType) || $value == 0) return;
        $this->_increaseData['credit' . $cType] = $value;
        return $this;
    }

    public function setCredit($cType, $value)
    {
        if (!$this->_isLegal($cType)) return;
        $this->_data['credit' . $cType] =  $value;
        return $this;
    }

    protected function _beforeAdd() {
        return true;
    }

    protected function _beforeUpdate() {
        if (!$this->uid) {
            return false;
        }
        if (empty($this->_data) && empty($this->_increaseData)) {
            return false;
        }
        return true;
    }

    /**
     * 积分字段合法性检查
     *
     * @param int $key
     * @return boolean
     */
    private function _isLegal(&$key)
    {
        $key = intval($key);
        return $key >= 1;
    }

}