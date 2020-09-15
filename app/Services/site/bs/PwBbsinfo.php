<?php

namespace App\Services\site\bs;

use App\Services\site\dm\PwBbsinfoDm;
use App\Services\site\ds\dao\PwBbsinfoDao;

class PwBbsinfo
{

    /**
     * 获取论坛信息
     *
     * @param int $id
     * @return array
     */
    public function getInfo($id)
    {
        if (empty($id)) return array();
        return $this->_getDao()->get($id);
    }

    /**
     * 更新论坛信息
     *
     * @param object $dm 更新信息
     * @return bool
     */
    public function updateInfo(PwBbsinfoDm $dm)
    {
        if (($result = $dm->beforeUpdate()) !== true) {
            return $result;
        }
        return $this->_getDao()->_update($dm->id, $dm->getData(), $dm->getIncreaseData());
    }

    protected function _getDao()
    {
        return app(PwBbsinfoDao::class);
    }
}