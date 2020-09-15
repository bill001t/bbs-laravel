<?php

namespace App\Services\like\dm;

use App\Core\BaseDm;
use App\Core\ErrorBag;

class PwLikeStatisticsDm extends BaseDm
{

    public function setSignkey($key)
    {
        $this->_data['signkey'] = $key;
        return $this;
    }

    public function setNumber($number)
    {
        $this->_data['number'] = (int)$number;
        return $this;
    }

    public function setLikeid($likeid)
    {
        $this->_data['likeid'] = (int)$likeid;
        return $this;
    }

    public function setTypeid($typeid)
    {
        $this->_data['typeid'] = (int)$typeid;
        return $this;
    }

    public function setFromid($fromid)
    {
        $this->_data['fromid'] = (int)$fromid;
        return $this;
    }


    protected function _beforeAdd()
    {
        return true;
    }

    protected function _beforeUpdate()
    {
        if ($this->likeid < 1) return new ErrorBag('BBS:like.likeid.empty');
        return true;
    }
}

?>