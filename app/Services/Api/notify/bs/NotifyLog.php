<?php

namespace App\Services\Api\notify\bs;

use App\Services\Api\notify\ds\dao\NotifyLogDao;
use App\Services\Api\notify\dm\NotifyLogDm;

class NotifyLog
{

    public function getLog($id)
    {
        $id = (int)$id;
        return $this->_getDao()->get($id);
    }

    public function getUncomplete($limit, $offset = 0)
    {
        return $this->_getDao()->getUncomplete($limit, $offset);
    }

    public function getList($appid = 0, $nid = 0, $limit = 10, $start = 0, $complete = null)
    {
        return $this->_getDao()->getList($appid, $nid, $limit, $start, $complete);
    }

    public function countList($appid = 0, $nid = 0, $complete = null)
    {
        return $this->_getDao()->countList($appid, $nid, $complete);
    }

    public function addLog(NotifyLogDm $dm)
    {
        if (($result = $dm->beforeAdd()) !== true) return $result;
        return $this->_getDao()->add($dm->getData());
    }

    public function multiAddLog($dms)
    {
        $data = array();
        foreach ($dms AS $dm) {
            if (($result = $dm->beforeAdd()) !== true) return $result;
            $data[] = $dm->getData();
        }
        if (!$data) return false;
        return $this->_getDao()->multiAdd($data);
    }

    public function updateLog(NotifyLogDm $dm)
    {
        if (($result = $dm->beforeUpdate()) !== true) return $result;
        return $this->_getDao()->_update($dm->logid, $dm->getData(), $dm->getIncreaseData());
    }


    public function deleteLog($id)
    {
        $id = (int)$id;
        return $this->_getDao()->_delete($id);
    }

    public function batchDelete($ids)
    {
        if (!is_array($ids)) $ids = array($ids);
        return $this->_getDao()->batchDelete($ids);
    }

    public function deleteComplete()
    {
        return $this->_getDao()->deleteComplete();
    }

    public function deleteByAppid($appid)
    {
        $appid = (int)$appid;
        return $this->_getDao()->deleteByAppid($appid);
    }

    private function _getDao()
    {
        return app(NotifyLogDao::class);
    }
}