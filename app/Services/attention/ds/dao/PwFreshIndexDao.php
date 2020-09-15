<?php

namespace App\Services\attention\ds\dao;

use App\Services\attention\ds\relation\PwFreshIndex;

class PwFreshIndexDao extends PwFreshIndex
{
    public function getByTid($tid)
    {
        return self::where('tid', $tid)
            ->get();
    }

    public function fetchByTid($tids)
    {
        return self::whereIn('tid', $tids)
            ->get();
    }

    public function add($fields)
    {
        return self::create($fields);
    }
}