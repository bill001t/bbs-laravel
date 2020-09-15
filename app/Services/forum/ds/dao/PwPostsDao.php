<?php

namespace App\Services\forum\ds\dao;

use App\Services\forum\ds\relation\posts;
use App\Services\forum\ds\traits\postsTrait;
use DB;

class PwPostsDao extends posts
{
    use postsTrait;

    public function getPost($pid)
    {
        return posts::find($pid);
    }

    public function fetchPost($pids)
    {
        return posts::whereIn('pid', $pids)
            ->get();
    }

    public function getPostByTid($tid, $perpage)
    {
        return posts::where('tid', $tid)
            ->paginate($perpage);
    }

    public function countPostByUid($uid)
    {
        return posts::where('uid', $uid)->count();
    }

    public function getPostByUid($uid, $perpage)
    {
        return posts::where('uid', $uid)
            ->paginate($perpage);
    }

    public function countPostByTidAndUid($tid, $uid)
    {
        return posts::where('tid', $tid)
            ->where('uid', $uid)
            ->where('disabled', '0')
            ->count();
    }

    public function countPostByTidUnderPid($tid, $pid)
    {
        return posts::where('tid', $tid)
            ->where('pid', '<', $pid)
            ->where('disabled', '0')
            ->count();
    }

    public function getPostByTidAndUid($tid, $uid, $perpage)
    {
        return posts::where('tid', $tid)
            ->where('disabled', '0')
            ->where('created_userid', $uid)
            ->orderby('created_time', 'asc')
            ->paginate($perpage);
    }

    public function addPost($fields)
    {
        return posts::create($fields);
    }

    public function updatePost($pid, $fields, $increaseFields = array())
    {
        return $this->_update($pid, $fields, $increaseFields = array());
    }

    public function batchUpdatePost($pids, $fields, $increaseFields = array())
    {
        return $this->_batchUpdate($pids, $fields, $increaseFields = array());
    }

    public function batchUpdatePostByTid($tids, $fields, $increaseFields = array())
    {
        foreach ($increaseFields as $k => $v) {
            self::whereIn('tid', $tids)
                ->increment($k, $v);
        }

        return self::whereIn('tid', $tids)
            ->update($fields);
    }

    public function revertPost($tids)
    {
        return DB::update('update ' . $this->table . ' set disabled =ischeck^1 where tid in (?)', [implode(',', $tids)]);
    }

    public function batchDeletePost($pids)
    {
        return posts::destroy($pids);
    }

    public function batchDeletePostByTid($tids)
    {
        return posts::whereIn('tid', $tids)
            ->delete();
    }

    public function getPostByPid($pid, $perpage)
    {
        return postsReply::whereHas('postsReply', function ($query) use ($pid) {
            $query->where('rpid', $pid);
        })->where('disabled', 0)
            ->orderby('pid', 'desc')
            ->paginate($perpage);
    }

    public function countUserPostByFidAndTime($fid, $time, $limit)
    {
        return posts::select(DB::raw('created_userid, COUNT(*) AS count'))
            ->where('disabled', 0)
            ->where('created_time', $time)
            ->where('fid', $fid)
            ->groupby('created_userid')
            ->orderby('count', 'desc')
            ->take($limit);
    }

    public function countPostsByFid()
    {
        return posts::select(DB::raw('fid, COUNT(*) AS sum'))
            ->where('disabled', 0)
            ->groupby('fid')
            ->get();
    }

    public function countDisabledPostByUid($uid)
    {
        return posts::where('created_userid', $uid)
            ->where('disabled', '<', 2)
            ->count();
    }

    public function getDisabledPostByUid($uid, $perpage)
    {
        return posts::where('created_userid', $uid)
            ->where('disabled', '<', 2)
            ->ordery('created_time', 'desc')
            ->paginate($perpage);
    }

    public function countSearchPost($field)
    {
        $sql = self::whereRaw('1=1');
        $this->_buildCondition($sql, $field);

        return $sql->count();
    }

    public function searchPost($field, $orderby, $perpage)
    {
        $sql = self::whereRaw('1=1');
        $this->_buildCondition($sql, $field);
        $this->_buildOrderby($sql, $orderby);

        return $sql->paginate($perpage);
    }

    protected function _buildCondition(&$sql, $field)
    {
        foreach ($field as $key => $value) {
            switch ($key) {
                case 'fid':
                    is_array($value) ? $sql->whereIn('fid', $value) : $sql->where('fid', $value);
                    break;
                case 'tid':
                    is_array($value) ? $sql->whereIn('tid', $value) : $sql->where('tid', $value);
                    break;
                case 'disabled':
                    $sql->where('disabled', 0);
                    break;
                case 'created_userid':
                    is_array($value) ? $sql->whereIn('created_userid', $value) : $sql->where('created_userid', $value);
                    break;
                case 'title_keyword':
                    $sql->where('subject', 'like', '%' . $value . '%');
                    break;
                case 'content_keyword':
                    $sql->where('content', 'like', '%' . $value . '%');
                    break;
                case 'title_and_content_keyword':
                    $sql->where(function ($query) use ($value) {
                        $query->where('subject', 'like', '%' . $value . '%');
                        $query->orWhere('content', 'like', '%' . $value . '%');
                    });
                    break;
                case 'created_time_start':
                    $sql->where('created_time', '>', $value);
                    break;
                case 'created_time_end':
                    $sql->where('created_time', '<', $value);
                    $arg[] = $value;
                    break;
                case 'created_ip':
                    $sql->where('created_ip', 'like', $value . '%');
                    break;
            }
        }
    }

    protected function _buildOrderby(&$sql, $orderby)
    {
        foreach ($orderby as $key => $value) {
            switch ($key) {
                case 'created_time':
                    $sql->orderby('created_time', ($value ? 'ASC' : 'DESC'));
                    break;
            }
        }
    }
}