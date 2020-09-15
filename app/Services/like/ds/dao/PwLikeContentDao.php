<?php

namespace App\Services\like\ds\dao;

use App\Services\like\ds\relation\PwLikeContent;

class PwLikeContentDao extends PwLikeContent
{
    public function getInfo($likeid)
    {
        return self::find($likeid);
    }

    public function fetchInfo($likeids)
    {
        return self::whereIn('likeid', $likeids)
            ->get();
    }

    public function getInfoByTypeidFromid($typeid, $fromid)
    {
        return self::where('typeid', $typeid)
            ->where('fromid', $fromid)
            ->first();
    }

    public function addInfo($data)
    {
        return self::create($data);
    }

    public function updateInfo($likeid, $data)
    {
        return self::where('likeid', $likeid)
            ->update($data);
    }

    public function deleteInfo($likeid)
    {
        return self::destroy($likeid);
    }
}

?>