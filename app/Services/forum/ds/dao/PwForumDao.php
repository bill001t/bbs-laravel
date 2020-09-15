<?php

namespace App\Services\forum\ds\dao;

use App\Services\forum\ds\relation\forum;
use App\Services\forum\ds\traits\forumTrait;

class PwForumDao extends forum
{
    use forumTrait;

    public function getForum($fid)
    {
        return self::find($fid);
    }

    public function fetchForum($fids)
    {
        return self::whereIn('fid', $fids)
            ->get();
    }

    public function searchForum($keyword)
    {
        return self::where('name', 'like', $keyword . '%')
            ->get();
    }

    public function getForumList()
    {
        return self::orderby('issub', 'asc')
            ->orderby('vieworder', 'asc')
            ->get();
    }

    public function getCommonForumList()
    {
        return self::where('issub', 0)
            ->orderby('vieworder', 'asc')
            ->get();
    }

    public function getSubForums($fid)
    {
        return self::where('parentid', $fid)
            ->orderby('vieworder', 'asc')
            ->get();
    }

    public function getForumOrderByType($asc = 'asc')
    {
        return self::all()
            ->orderby('type', $asc);
    }

    public function addForum($fields)
    {
        return self::create($fields);
    }

    public function updateForum($fid, $fields, $increaseFields = array())
    {
        return self::where('fid', $fid)
            ->update($fields);
    }

    public function batchUpdateForum($fids, $fields)
    {
        return self::whereIn('fid', $fids)
            ->update($fields);
    }

    public function deleteForum($fid)
    {
        return self::destroy($fid);
    }
}