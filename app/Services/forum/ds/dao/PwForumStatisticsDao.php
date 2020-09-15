<?php

namespace App\Services\forum\ds\dao;

use App\Services\forum\ds\relation\forumStatistics;
use App\Services\forum\ds\traits\forumStatisticsTrait;
use DB;

class PwForumStatisticsDao extends forumStatistics
{
    use forumStatisticsTrait;

    public function getForumStatistics($fid)
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

    public function updateForumStatistics($fid, $subFids)
    {
        if ($subFids) {
            return DB::update('UPDATE ' . $this->table . ' a LEFT JOIN (SELECT fid as fid,sum(threads+subthreads) as subthreads,sum(article) as subarticle FROM pw_bbs_forum_statistics WHERE fid IN (?)) b on a.fid=b.fid SET a.article=a.threads+a.posts+b.subarticle,a.subthreads=b.subthreads WHERE a.fid=?', [implode(',', $subFids), $fid]);
        } else {
            return DB::update('UPDATE ' . $this->table . ' SET article=threads+posts,subthreads=0 WHERE fid=' . [$fid]);
        }
    }
}