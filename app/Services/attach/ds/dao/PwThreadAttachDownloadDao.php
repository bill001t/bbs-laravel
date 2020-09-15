<?php

namespace App\Services\attach\ds\dao;

use App\Services\attach\ds\relation\ThreadAttachDownload;

class PwThreadAttachDownloadDao extends ThreadAttachDownload
{
    public function sumCost($aid)
    {
        return self::where('aid', $aid)
            ->count();
    }

    public function get($id)
    {
        return self::find($id);
    }

    public function countByAid($aid)
    {
        return self::where('aid', $aid)
            ->count();
    }

    public function getByAid($aid, $limit, $offset)
    {
        return self::where('aid', $aid)
            ->orderby('created_time', 'DESC')
            ->paginate($limit);
    }

    public function getByAidAndUid($aid, $uid)
    {
        return self::where('aid', $aid)
            ->where('created_userid', $uid)
            ->get();
    }

    public function add($fields)
    {
        return self::create($fields);
    }
}