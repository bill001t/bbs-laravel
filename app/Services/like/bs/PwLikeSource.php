<?php

namespace App\Services\like\bs;

use App\Core\ErrorBag;
use App\Services\like\dm\PwLikeSourceDm;
use App\Services\like\ds\dao\PwLikeSourceDao;

class PwLikeSource
{

    public function getSource($sid)
    {
        $sid = (int)$sid;
        if ($sid < 1) return array();
        return $this->_getDao()->getSource($sid);
    }

    public function getSourceByAppAndFromid($fromapp, $fromid)
    {
        if (empty($fromapp) && $fromid < 1) return array();
        return $this->_getDao()->getSourceByAppAndFromid($fromapp, $fromid);
    }

    public function fetchSource($ids)
    {
        if (!is_array($ids) || count($ids) < 1) return array();
        return $this->_getDao()->fetchSource($ids);
    }

    public function addSource(PwLikeSourceDm $dm)
    {
        $resource = $dm->beforeAdd();
        if ($resource instanceof ErrorBag) return $resource;
        return $this->_getDao()->addSource($dm->getData());
    }

    public function deleteSource($sid)
    {
        $sid = (int)$sid;
        if ($sid < 1) return array();
        return $this->_getDao()->deleteSource($sid);
    }

    public function updateSource(PwLikeSourceDm $dm)
    {
        $resource = $dm->beforeUpdate();
        if ($resource instanceof ErrorBag) return $resource;
        return $this->_getDao()->updateSource($dm->sid, $dm->getData());
    }


    private function _getDao()
    {
        return app(PwLikeSourceDao::class);
    }
}

?>