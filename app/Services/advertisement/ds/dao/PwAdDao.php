<?php

namespace App\Services\advertisement\ds\dao;

use App\Services\advertisement\ds\relation\PwAd;

class PwAdDao extends PwAd
{
    public function getAllAd()
    {
        return self::all();
    }

    public function addAdPosition($data)
    {
        return self::create($data);
    }

    public function editAdPosition($pid, $data)
    {
        return self::where('pid', $pid)
            ->update($data);
    }

    public function get($pid)
    {
        return self::find($pid);
    }

    public function getByIdentifier($identifier)
    {
        return self::where('identifier', $identifier)
            ->first();
    }

}