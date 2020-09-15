<?php

namespace App\Services\online\ds\dao;

use App\Services\online\ds\relation\PwOnlineStatistics;

class PwOnlineStatisticsDao extends PwOnlineStatistics
{
    public function getInfo($key)
    {
        return self::find($key);
    }

    public function addInfo($data)
    {
        return self::firstOrCreate($data);
    }

    public function deleteInfo($key)
    {
        return self::destroy($key);
    }


}

?>