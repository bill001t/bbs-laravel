<?php

namespace App\Services\Api\notify\ds\dao;

use App\Services\Api\notify\ds\relation\Notify;

class NotifyDao extends Notify
{
    /**
     * 根据ID获取信息
     *
     * @param int $nid
     * @return array|boolean
     */
    public function get($nid)
    {
        return self::find($nid);
    }

    public function fetch($nids)
    {
        return self::whereIn('nid', $nids)
            ->get();
    }

    /**
     * 根据应用ID获取信息
     *
     * @param int $appid 应用ID
     * @return array|false
     */
    public function getByAppid($appid)
    {
        return self::where('appid', $appid)
            ->get();
    }

    public function add($data)
    {
        return self::create($data);
    }

    public function batchAdd($data)
    {
        return self::create($data);
    }


    public function _update($nid, $data)
    {
        return self::where('nid', $nid)
            ->update($data);
    }

    public function _delete($nid)
    {
        return self::destroy($nid);
    }


    public function batchDelete($nids)
    {
        return self::destroy($nids);
    }

    public function batchNotDelete($nids)
    {
        return self::whereNotIn('nid', $nids)
            ->delete();
    }

    public function deleteAll()
    {
        return self::truncate();
    }

}