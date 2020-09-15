<?php

namespace App\Services\online\dm;

use App\Core\BaseDm;
use App\Core\ErrorBag;

class PwOnlineDm extends BaseDm
{

    public function setIp($ip)
    {
        $this->_data['ip'] = intval($ip);
        return $this;
    }

    public function setUid($uid)
    {
        $this->_data['uid'] = intval($uid);
        return $this;
    }

    public function setUsername($username)
    {
        $this->_data['username'] = $username;
        return $this;
    }

    public function setModifytime($time)
    {
        $this->_data['modify_time'] = intval($time);
        return $this;
    }

    public function setCreatedtime($time)
    {
        $this->_data['created_time'] = intval($time);
        return $this;
    }

    public function setTid($tid)
    {
        $this->_data['tid'] = intval($tid);
        return $this;
    }

    public function setFid($fid)
    {
        $this->_data['fid'] = intval($fid);
        return $this;
    }

    public function setGid($gid)
    {
        $this->_data['gid'] = intval($gid);
        return $this;
    }

    public function setRequest($mca)
    {
        $this->_data['request'] = $mca;
        return $this;
    }

    protected function _beforeAdd()
    {
        if (!$this->_data['ip'] && !$this->_data['uid']) return new ErrorBag('fail');
        return true;
    }

    protected function _beforeUpdate()
    {
        if (!$this->_data['ip'] && !$this->_data['uid']) return new ErrorBag('fail');
        return true;
    }

}

?>