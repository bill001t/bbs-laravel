<?php

namespace App\Services\forum\ds\dao;

use App\Services\forum\ds\relation\forumExtra;
use App\Services\forum\ds\traits\forumExtraTrait;

class PwForumExtraDao extends forumExtra
{
    use forumExtraTrait;

    public function getForum($fid)
    {
        return self::find($fid);
    }

    public function getForumExtra($fid)
    {
        return self::find($fid);
    }

    public function fetchForum($fids)
    {
        return self::whereIn('fid', $fids)
            ->get();
    }

    public function getForumList()
    {

    }

    public function getCommonForumList()
    {

    }

    public function addForum($fields)
    {
        return self::create($fields);
    }

    public function updateForum($fid, $fields, $increaseFields = array())
    {
        return $this->_update($fid, $fields, $increaseFields);
    }

    public function batchUpdateForum($fids, $fields, $increaseFields = array())
    {
        return $this->_batchUpdate($fids, $fields, $increaseFields);
    }

    public function deleteForum($fid)
    {
        return self::destroy($fid);
    }
}