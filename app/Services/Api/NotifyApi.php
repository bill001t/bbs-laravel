<?php

namespace App\Services\Api;

use App\Services\notify\bm\NotifyServer;

class WindidNotifyApi
{

    public function fetchNotify($nids)
    {
        return $this->_getNotifyDs()->fetchNotify($nids);
    }

    public function batchNotDelete($nids)
    {
        return $this->_getNotifyDs()->batchNotDelete($nids);
    }

    public function getlogList($appid = 0, $nid = 0, $limit = 10, $start = 0, $complete = null)
    {
        return $this->_getNotifyLogDs()->getList($appid, $nid, $limit, $start, $complete);
    }

    public function countLogList($appid = 0, $nid = 0, $complete = null)
    {
        return $this->_getNotifyLogDs()->countList($appid, $nid, $complete);
    }

    public function deleteLogComplete()
    {
        return $this->_getNotifyLogDs()->deleteComplete();
    }

    public function deleteLog($logid)
    {
        return $this->_getNotifyLogDs()->deleteLog($logid);
    }

    public function logSend($logid)
    {
        return $this->_getNotifyService()->logSend($logid);
    }

    private function _getNotifyDs()
    {
        return app(Notify::class);
    }

    private function _getNotifyLogDs()
    {
        return app(NotifyLog::class);
    }

    private function _getNotifyService()
    {
        return app(NotifyServer::class);
    }
}

?>