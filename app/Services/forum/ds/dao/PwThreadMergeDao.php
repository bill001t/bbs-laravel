<?php

namespace App\Services\forum\ds\dao;

use App\Services\forum\ds\relation\threads;
use App\Services\forum\ds\traits\threadsTrait;
use DB;

class PwThreadMergeDao extends threads
{
    use threadsTrait;

    public function countSearchThread($field)
    {
        $threads = threads::whereRaw('1 = 1');
        $threads = $this->_buildCondition($threads, $field);

        return $threads->count();
    }

    public function searchThread($fetch, $field, $orderby, $limit, $offset)
    {
        $threads = threads::whereRaw('1 = 1');
        $threads = $this->_buildCondition($threads, $field);
        $threads = $this->_buildOrderby($threads, $orderby);

        return $threads;
    }

    protected function _buildCondition($threads, $field)
    {
        foreach ($field as $key => $value) {
            switch ($key) {
                case 'tid':
                    (is_array($value)) ? $threads->whereIn('tid', implode($value)) : $threads->where('tid', $value);
                    break;
                case 'fid':
                    (is_array($value)) ? $threads->whereIn('fid', implode($value)) : $threads->where('fid', $value);
                    break;
                case 'topic_type':
                    (is_array($value)) ? $threads->whereIn('topic_type', implode($value)) : $threads->where('topic_type', $value);
                    break;
                case 'disabled':
                    $threads->where('disabled', $value);
                    break;
                case 'created_userid':
                    (is_array($value)) ? $threads->whereIn('created_userid', implode($value)) : $threads->where('created_userid', $value);
                    break;
                case 'title_keyword':
                    $threads->where('title_keyword', 'like', "%$value%");
                    break;
                case 'content_keyword':
                    $threads->load('threadsContent');
                    $threads->whereHas('threadsContent', function ($query) use ($value) {
                        $query->where('content', 'like', "%$value%");
                    });
                    break;
                case 'title_and_content_keyword':
                    $threads->where(function ($query) use ($value) {
                        $query->whereHas('threadsContent', function ($query) use ($value) {
                            $query->where('content', 'like', "%$value%");
                        })->orWhere('subject', 'like', "%$value%");
                    });
                    break;
                case 'created_time_start':
                    $threads->where('created_time', '>', $value);
                    break;
                case 'created_time_end':
                    $threads->where('created_time', '<', $value);
                    break;
                case 'lastpost_time_start':
                    $threads->where('lastpost_time', '>', $value);
                    break;
                case 'lastpost_time_end':
                    $threads->where('lastpost_time', '<', $value);
                    break;
                case 'digest':
                    $threads->where('digest', $value);
                    break;
                case 'hasimage':
                    $threads->where('ifupload&1', intval($value));
                    break;
                case 'special':
                    (is_array($value)) ? $threads->whereIn('special', implode($value)) : $threads->where('special', $value);
                    break;
                case 'topped':
                    (is_array($value)) ? $threads->whereIn('topped', implode($value)) : $threads->where('topped', $value);
                    break;
                case 'hits_start':
                    $threads->where('hits', '>', $value);
                    break;
                case 'hits_end':
                    $threads->where('hits', '<', $value);
                    break;
                case 'replies_start':
                    $threads->where('replies', '>', $value);
                    break;
                case 'replies_end':
                    $threads->where('replies', '<', $value);
                    break;
                case 'created_ip':
                    $threads->where('created_ip', 'like', "$value%");
                    break;
            }
        }

        return $threads;
    }

    protected function _buildOrderby($threads, $orderby)
    {
        foreach ($orderby as $key => $value) {
            switch ($key) {
                case 'created_time':
                    $threads->orderby('created_time', ($value ? 'ASC' : 'DESC'));
                    break;
                case 'lastpost_time':
                    $threads->orderby('lastpost_time', ($value ? 'ASC' : 'DESC'));
                    break;
                case 'replies':
                    $threads->orderby('replies', ($value ? 'ASC' : 'DESC'));
                    break;
                case 'hits':
                    $threads->orderby('hits', ($value ? 'ASC' : 'DESC'));
                    break;
                case 'like':
                    $threads->orderby('like_count', ($value ? 'ASC' : 'DESC'));
                    break;
            }
        }
        return $threads;
    }
}