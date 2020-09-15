<?php

namespace App\Services\space\ds\dao;

use App\Services\space\ds\relation\PwSpace;

class PwSpaceDao extends PwSpace
{
    public function getSpace($uid)
    {
        return self::find($uid);
    }

    public function fetchSpace($uids)
    {
        return self::whereIn('uid', $uids)
            ->get();
    }

    public function getSpaceByDomain($domain)
    {
        return self::where('space_domain', $domain)
            ->first();
    }

    public function addInfo($data)
    {
        return self::create($data);
    }

    public function updateInfo($uid, $data)
    {
        return self::where('uid', $uid)
            ->update($data);
    }

    public function updateNumber($uid)
    {
        return self::where('uid', $uid)
            ->increment('visit_count');
    }

    public function deleteInfo($uid)
    {
        return self::destroy($uid);
    }
}

?>