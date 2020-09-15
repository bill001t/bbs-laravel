<?php

namespace App\Services\forum\ds\dao;

use App\Services\forum\ds\relation\postsReply;
use App\Services\forum\ds\traits\postsReplyTrait;
use DB;

class PwPostsReplyDao extends postsReply
{
    use postsReplyTrait;

    public function getPostByPid($pid, $limit, $offset)
    {
        return DB::select($this->_bindSql('SELECT b.* FROM %s a LEFT JOIN %s b ON a.pid=b.pid WHERE a.rpid=? AND b.disabled=0 ORDER BY a.pid DESC limit ?,?', $this->table, $this->_mergeTable), [$pid, $offset, $limit]);
    }

    public function add($fields)
    {
        return postsReply::firstOrCreate($fields);
    }
}