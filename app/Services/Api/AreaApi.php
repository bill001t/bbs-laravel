<?php

namespace App\Services\Api;

use App\Core\Utility;
use App\Services\Api\area\bm\AreaService;
use App\Services\Api\area\dm\AreaDm;

class AreaApi
{

    public function getArea($id)
    {
        return $this->_getAreaDs()->getArea($id);
    }

    public function fetchArea($ids)
    {
        return $this->_getAreaDs()->fetchByAreaid($ids);
    }

    public function getByParentid($parentid)
    {
        return $this->_getAreaDs()->getAreaByParentid($parentid);
    }

    public function getAll()
    {
        return $this->_getAreaDs()->fetchAll();
    }

    public function getAreaInfo($areaid)
    {
        return $this->_getAreaService()->getAreaInfo($areaid);
    }

    public function fetchAreaInfo($areaids)
    {
        return $this->_getAreaService()->fetchAreaInfo($areaids);
    }

    public function getAreaRout($areaid)
    {
        return $this->_getAreaService()->getAreaRout($areaid);
    }

    public function fetchAreaRout($areaids)
    {
        return $this->_getAreaService()->fetchAreaRout($areaids);
    }

    public function getAreaTree()
    {
        return $this->_getAreaService()->getAreaTree();
    }

    public function updateArea(AreaDm $dm)
    {
        $result = $this->_getAreaDs()->updateArea($dm);
        return Utility::result($result);
    }

    public function batchAddArea($dms)
    {
        $result = $this->_getAreaDs()->batchAddArea($dms);
        return Utility::result($result);
    }

    public function deleteArea($areaid)
    {
        $result = $this->_getAreaDs()->deleteArea($areaid);
        return Utility::result($result);
    }

    private function _getAreaDs()
    {
        return app(Area::class);
    }

    private function _getAreaService()
    {
        return app(AreaService::class);
    }
}

?>