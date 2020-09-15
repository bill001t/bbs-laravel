<?php

namespace App\Services\forum\dm;

use App\Core\BaseDm;
use App\Core\ErrorBag;
use App\Core\Tool;

class PwSpecialSortDm extends BaseDm
{

    private $tid;
    private $fid;
    private $pid = 0;

    public function __construct()
    {
//		$this->fid = intval($fid);
//		$this->tid = intval($tid);
//		$this->pid = intval($pid);
    }

    public function setType($type)
    {
        $this->_data['sort_type'] = $type;
    }

    public function setTopped($topped)
    {
        $topped = intval($topped);
        $this->_data['topped'] = $topped;
    }

    public function setFid($fid)
    {
        $fid = intval($fid);
        $this->_data['fid'] = $fid;
    }

    public function setTid($tid)
    {
        $fid = intval($tid);
        $this->_data['tid'] = $tid;
    }

    public function setPid($pid)
    {
        $pid = intval($pid);
        $this->_data['pid'] = $pid;
    }

    public function setExtra($extra)
    {
        $this->_data['extra'] = intval($extra);
    }

    public function setEndtime($endtime)
    {
        $endtime = intval($endtime);
        $this->_data['end_time'] = $endtime;
    }

    public function getFid()
    {
        return $this->fid;
    }

    public function getTid()
    {
        return $this->tid;
    }

    public function getPid()
    {
        return $this->pid;
    }

    public function _beforeAdd()
    {
        if (empty($this->_data['tid'])) {
            return new ErrorBag('FORUM:headtopic.threaderror');
        }
        /*
            if (!$this->_data['topped']) {
                return new ErrorBag('FORUM:headtopic.toppederror');
            }
        */
        $this->_data['created_time'] = Tool::getTime();

        return true;
    }

    public function _beforeUpdate()
    {
        if ($this->tid < 1) {
            return new ErrorBag('FORUM:headtopic.threaderror');
        }
        return true;
    }
}