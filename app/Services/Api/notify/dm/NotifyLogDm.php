<?php

namespace App\Services\Api\notify\dm;

use App\Core\BaseDm;


class NotifyLogDm extends BaseDm
{

    public $logid;

    public function __construct($logid = null)
    {
        isset($logid) && $this->logid = $logid;
    }

    public function setNid($nid)
    {
        $this->_data['nid'] = intval($nid);
        return $this;
    }

    public function setAppid($appid)
    {
        $this->_data['appid'] = intval($appid);
        return $this;
    }

    public function setComplete($complete)
    {
        $this->_data['complete'] = intval($complete);
        return $this;
    }

    public function setSendNum($num)
    {
        $this->_data['send_num'] = intval($num);
        return $this;
    }

    public function setReason($reason)
    {
        $this->_data['reason'] = $reason;
        return $this;
    }

    public function setIncreaseSendNum($num)
    {
        $this->_increaseData['send_num'] = intval($num);
        return $this;
    }

    protected function _beforeAdd()
    {

        return true;
    }

    protected function _beforeUpdate()
    {

        return true;
    }
}