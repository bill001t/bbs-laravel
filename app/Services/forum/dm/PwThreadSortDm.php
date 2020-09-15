<?php

namespace App\Services\forum\dm;

use App\Core\BaseDm;
use App\Core\ErrorBag;

class PwThreadSortDm extends BaseDm
{

    public function setType($type)
    {
        $this->_data['sort_type'] = $type;
        return $this;
    }

    public function setFid($fid)
    {
        $this->_data['fid'] = intval($fid);
        return $this;
    }

    public function setTid($tid)
    {
        $this->_data['tid'] = intval($tid);
        return $this;
    }

    public function setExtra($extra)
    {
        $this->_data['extra'] = intval($extra);
        return $this;
    }

    public function setCreatedtime($time)
    {
        $this->_data['created_time'] = intval($time);
        return $this;
    }

    public function setEndtime($endtime)
    {
        $this->_data['end_time'] = intval($endtime);
        return $this;
    }

    public function _beforeAdd()
    {
        if (empty($this->_data['fid']) || empty($this->_data['tid'])) {
            return new ErrorBag('data.miss');
        }
        return true;
    }

    public function _beforeUpdate()
    {
        return true;
    }
}