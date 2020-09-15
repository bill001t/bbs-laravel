<?php

namespace App\Services\like\ds\dao;

use App\Services\like\ds\relation\PwLikeTag;
use DB;

class PwLikeTagDao extends PwLikeTag
{
    public function getInfo($tagid)
    {
        return self::find($tagid);
    }

    public function getInfoByTags($tagids)
    {
        return self::whereIn('tagid', $tagids)
            ->get();
    }

    public function getInfoByUid($uid)
    {
        return self::where('uid', $uid)
            ->get();
    }

    public function addInfo($data)
    {
        return self::create($data);
    }

    public function updateInfo($tagid, $data)
    {
        return self::where('tagid', $tagid)
            ->update($data);
    }

    public function updateNumber($tagid, $type = true)
    {
        $sql = self::where('tagid', $tagid);

        if ($type) {
            return $sql->increment('number');
        } else {
            return $sql->decrement('number');
        }

    }

    public function deleteInfo($tagid)
    {
        return self::destroy($tagid);
    }

}

?>