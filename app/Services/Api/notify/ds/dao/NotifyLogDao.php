<?php

namespace App\Services\Api\notify\ds\dao;

use App\Services\Api\notify\ds\relation\NotifyLog;

class NotifyLogDao extends NotifyLog
{
    public function get($id)
    {
        return self::find($id);
    }

    public function getUncomplete($limit, $offset)
    {
        return self::where('complete', 0)
            ->where('send_num', '<', 4)
            ->orderby('logid', 'desc')
            ->paginate($limit);
    }

    public function getList($appid, $nid, $limit, $start, $complete = null)
    {
        $sql = self::orderby('logid', 'desc');

        if ($appid) {
            $sql = $sql->where('appid', $appid);
        }
        if ($nid) {
            $sql = $sql->where('nid', $nid);
        }
        if (isset($complete)) {
            $sql = $sql->where('complete', $complete);
        }

        return $sql->paginate($limit);
    }

    public function countList($appid, $nid, $complete = null)
    {
        $sql = self::whereRaw('1 = 1');

        if ($appid) {
            $sql = $sql->where('appid', $appid);
        }
        if ($nid) {
            $sql = $sql->where('nid', $nid);
        }
        if (isset($complete)) {
            $sql = $sql->where('complete', $complete);
        }

        return $sql->count();
    }

    public function add($data)
    {
        return self::create($data);
    }

    public function multiAdd($data)
    {
        if (!$data) return false;
        foreach ($data AS $k => $v) {
            self::create($v);
        }

        return true;
    }

    public function _update($id, $data, $increase)
    {
        foreach ($increase as $k => $v) {
            self::where('id', $id)
                ->increment($k, $v);
        }

        return self::where('id', $id);

    }

    public function _delete($id)
    {
        return self::destroy($id);
    }

    public function deleteByAppid($appid)
    {
        return self::where('appid', $appid)
            ->delete();
    }

    public function deleteComplete()
    {
        return self::where('complete', 1)
            ->delete();
    }

    public function batchDelete($ids)
    {
        return self::destroy($ids);
    }
}