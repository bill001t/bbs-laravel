<?php

namespace App\Services\forum\ds\dao;

use App\Services\forum\ds\relation\forum;
use App\Services\forum\ds\traits\forumTrait;
use DB;

class PwForumAllDao extends forum
{
    use forumTrait;

    protected $_extraTable = '_bbs_forum_extra';
    protected $_statisticsTable = '_bbs_forum_statistics';

    public function getForum($fid)
    {
        return self::leftJoin($this->_extraTable . ' as e', $this->table . '.fid', '=', 'e.fid')
            ->leftJoin($this->_statisticsTable . ' as s', $this->table . '.fid', '=', 's.fid')
            ->select(DB::raw($this->table . '.*, e.*, s.*'))
            ->where($this->table . '.fid', $fid)
            ->first();
    }

    public function fetchForum($fids)
    {
        return self::leftJoin($this->_extraTable . ' as e', $this->table . '.fid', '=', 'e.fid')
            ->leftJoin($this->_statisticsTable . ' as s', $this->table . '.fid', '=', 's.fid')
            ->select(DB::raw($this->table . '.*, e.*, s.*'))
            ->whereIn('fid', $fids)
            ->get();
    }

    public function getForumList()
    {
        return self::leftJoin($this->_extraTable . ' as e', $this->table . '.fid', '=', 'e.fid')
            ->leftJoin($this->_statisticsTable . ' as s', $this->table . '.fid', '=', 's.fid')
            ->select(DB::raw($this->table . '.*, e.*, s.*'))
            ->orderby('issub', 'asc')
            ->orderby('vieworder', 'asc')
            ->get();
    }

    public function getCommonForumList()
    {
        return self::leftJoin($this->_extraTable . ' as e', $this->table . '.fid', '=', 'e.fid')
            ->leftJoin($this->_statisticsTable . ' as s', $this->table . '.fid', '=', 's.fid')
            ->select(DB::raw($this->table . '.*, e.*, s.*'))
            ->where('issub', 0)
            ->orderby('vieworder', 'asc')
            ->get();
    }

    public function addForum($fields)
    {
        $forum = forum::create($fields);
        $forum->forumExtra()->create($fields);
        $forum->forumStatistics()->create($fields);

        return true;
    }

    public function updateForum($fid, $fields, $increaseFields = array())
    {
        app(PwForumDao::class)->updateForum($fid, $fields, $increaseFields = array());
        app(PwForumExtraDao::class)->updateForum($fid, $fields, $increaseFields = array());
        app(PwForumStatisticsDao::class)->updateForum($fid, $fields, $increaseFields = array());

        return true;
    }

    public function batchUpdateForum($fids, $fields, $increaseFields = array())
    {
        app(PwForumDao::class)->batchUpdateForum($fids, $fields, $increaseFields = array());
        app(PwForumExtraDao::class)->batchUpdateForum($fids, $fields, $increaseFields = array());
        app(PwForumStatisticsDao::class)->batchUpdateForum($fids, $fields, $increaseFields = array());

        return true;
    }

    public function deleteForum($fid)
    {
        app(PwForumDao::class)->deleteForum($fid);
        app(PwForumExtraDao::class)->deleteForum($fid);
        app(PwForumStatisticsDao::class)->deleteForum($fid);

        return true;
    }
}