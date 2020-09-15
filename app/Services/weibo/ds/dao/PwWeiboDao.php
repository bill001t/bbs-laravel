<?php

namespace App\Services\weibo\ds\dao;

use App\Services\weibo\ds\relation\PwWeibo;

class PwWeiboDao extends PwWeibo
{
    public function getWeibo($weiboId)
    {
        return self::find($weiboId);
    }

    public function fetchWeibo($weiboIds)
    {
        return self::whereIn('weibo_id', $weiboIds)
            ->get();
    }

    public function addWeibo($fields)
    {
        return self::create($fields);
    }

    public function updateWeibo($weiboId, $fields, $increaseFields = array())
    {
        $sql = self::where('weibo_id', $weiboId);

        foreach($increaseFields as $k => $v){
            $sql->increment($k, $v);
        }

        return $sql->update($fields);
    }

    public function deleteWeibo($weiboId)
    {
        return self::destroy($weiboId);
    }

    public function batchDeleteWeibo($weiboIds)
    {
        return self::destroy($weiboIds);
    }
}