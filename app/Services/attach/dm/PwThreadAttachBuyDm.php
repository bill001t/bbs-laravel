<?php

namespace App\Services\attach\dm;

use App\Core\BaseDm;

class PwThreadAttachBuyDm extends BaseDm
{

    public function setAid($aid)
    {
        $this->_data['aid'] = intval($aid);
        return $this;
    }

    public function setCreatedUserid($uid)
    {
        $this->_data['created_userid'] = intval($uid);
        return $this;
    }

    public function setCreatedTime($time)
    {
        $this->_data['created_time'] = $time;
        return $this;
    }

    public function setCtype($ctype)
    {
        $this->_data['ctype'] = intval($ctype);
        return $this;
    }

    public function setCost($cost)
    {
        $this->_data['cost'] = intval($cost);
        return $this;
    }

    public function _beforeAdd()
    {
        return true;
    }

    public function _beforeUpdate()
    {
        return true;
    }
}