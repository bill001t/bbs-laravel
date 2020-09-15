<?php

namespace App\Services\attach\ds\dao;

use App\Services\attach\ds\relation\Attach;

class PwAttachDao extends Attach
{
    public function getAttach($aid)
    {
        return self::find($aid);
    }

    public function fetchAttach($aids)
    {
        return self::where('aid', $aids)
            ->get();
    }

    public function addAttach($fields)
    {
        return self::create($fields);
    }

    public function updateAttach($aid, $fields)
    {
        return self::where('aid', $aid)
            ->update($fields);
    }

    public function batchUpdateAttach($aids, $fields)
    {
        return self::whereIn('aid', $aids)
            ->update($fields);
    }

    public function deleteAttach($aid)
    {
        return self::destroy($aid);
    }

    public function batchDeleteAttach($aids)
    {
        return self::destroy($aids);
    }
}