<?php

namespace App\Services\Api\message\bm\vo;

class WindidMessageSo
{

    protected $_data = array();

    public function getData()
    {
        return $this->_data;
    }

    public function setFromUid($fromuid)
    {
        $this->_data['fromuid'] = (int)$fromuid;
    }

    public function setToUid($touid)
    {
        $this->_data['touid'] = (int)$touid;
    }

    public function setKeyword($keyword)
    {
        $this->_data['keyword'] = $keyword;
    }

    public function setStarttime($starttime)
    {
        $this->_data['starttime'] = $starttime;
    }

    public function setEndTime($time)
    {
        $this->_data['endtime'] = $time;
    }

}

?>