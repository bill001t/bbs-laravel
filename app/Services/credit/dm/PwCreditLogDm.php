<?php

namespace App\Services\credit\dm;

use App\Core\BaseDm;

class PwCreditLogDm extends BaseDm
{
    public function setCtype($ctype)
    {
        $this->_data['ctype'] = intval($ctype);
        return $this;
    }

    public function setAffect($affect)
    {
        $this->_data['affect'] = intval($affect);
        return $this;
    }

    public function setLogtype($logtype)
    {
        $this->_data['logtype'] = $logtype;
        return $this;
    }

    public function setDescrip($descrip)
    {
        $this->_data['descrip'] = $descrip;
        return $this;
    }

    public function setCreatedUser($uid, $username)
    {
        $this->_data['created_userid'] = $uid;
        $this->_data['created_username'] = $username;
        return $this;
    }

    public function setCreatedTime($time)
    {
        $this->_data['created_time'] = $time;
        return $this;
    }

    protected function _beforeAdd()
    {
        $this->_data['descrip'] && ($this->_data['descrip'] = strip_tags($this->_data['descrip']));
        return true;
    }

    protected function _beforeUpdate()
    {
        $this->_data['descrip'] && ($this->_data['descrip'] = strip_tags($this->_data['descrip']));
        return true;
    }
}