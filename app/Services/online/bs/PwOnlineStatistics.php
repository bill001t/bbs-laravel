<?php

namespace App\Services\online\bs;

use App\Services\online\ds\dao\PwOnlineStatisticsDao;

class PwOnlineStatistics
{

    /**
     * 获取一条记录
     *
     * @param string $key
     * @return array
     */
    public function getInfo($key)
    {
        return $this->_getOnlineStatisticsDao()->getInfo($key);
    }

    /**
     * 增加一条记录
     *
     * @param string $key
     * @param int $count
     * @return bool
     */
    public function addInfo($key, $number = 0, $time = 0)
    {
        $number = (int)$number;
        $data = array(
            'signkey' => $key,
            'number' => $number,
            'created_time' => $time
        );
        return $this->_getOnlineStatisticsDao()->addInfo($data);
    }

    /**
     * 删除一条记录
     *
     * @param string $key
     */
    public function deleteInfo($key)
    {
        return $this->_getOnlineStatisticsDao()->deleteInfo($key);
    }

    private function _getOnlineStatisticsDao()
    {
        return app(PwOnlineStatisticsDao::class);
    }
}

?>