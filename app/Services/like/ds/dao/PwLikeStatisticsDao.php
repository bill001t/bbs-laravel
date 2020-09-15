<?php

namespace App\Services\like\ds\dao;

use App\Services\like\ds\relation\PwLikeStatistics;
use DB;

class PwLikeStatisticsDao extends PwLikeStatistics
{
    public function getInfo($signkey)
    {
        return self::find($signkey);
    }

    public function getInfoByLikeid($signkey, $likeid)
    {
        return self::where('signkey', $signkey)
            ->where('likeid', $likeid)
            ->first();
    }

    public function fetchInfo($signkeys)
    {
        return self::whereIn('signkey', $signkeys)
            ->get();
    }

    public function getInfoList($signkey, $offset, $limit, $typeid)
    {
        $sql = self::where('signkey', $signkey);

        if ($typeid) {
            $sql = $sql->where('typeid', $typeid);
        }

        return $sql->orderby('number', 'DESC')
            ->paginate($limit);
    }

    public function getMinInfo($signkey)
    {
        return self::where('signkey', $signkey)
            ->orderby('number', 'DESC')
            ->first();
    }

    public function countSignkey($signkey)
    {
        return self::where('signkey', $signkey)
            ->count();
    }


    public function addInfo($data)
    {
        return self::create($data);
    }

    public function updateInfo($data)
    {
        return self::where('signkey', $data['signkey'])
            ->where('likeid', $data['likeid'])
            ->update($data);
    }

    public function deleteInfo($signkey, $likeid)
    {
        return self::where('signkey', $signkey)
            ->where('likeid', $likeid)
            ->delete();
    }

    public function deleteInfos($signkey)
    {
        return self::where('signkey', $signkey)
            ->delete();
    }

}

?>