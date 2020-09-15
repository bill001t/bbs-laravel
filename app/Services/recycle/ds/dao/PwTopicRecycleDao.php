<?php

namespace App\Services\recycle\ds\dao;

use App\Services\forum\ds\relation\PwThread;
use App\Services\recycle\ds\relation\PwTopicRecycle;

/**
 * 主题回收站记录数据表
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PwTopicRecycleDao.php 14354 2012-07-19 10:36:06Z jieyin $
 * @package src.service.user.dao
 */
class PwTopicRecycleDao extends PwTopicRecycle
{
    public function fetchRecord($tids)
    {
        return self::whereIn('tid', $tids)
            ->get();
    }

    public function add($fields)
    {
        return self::create($fields);
    }

    public function batchAdd($data)
    {
        foreach ($data as $key => $value) {
            $fields[] = array($value['tid'], $value['fid'], $value['operate_time'], $value['operate_username'], $value['reason']);
        }
        if (!$fields) return false;

        return self::firstOrCreate($fields);
    }

    public function batchDelete($tids)
    {
        return self::destroy($tids);
    }

    public function countSearchRecord($field)
    {
        $sql = self::whereRaw('1 = 1');

        $sql = $this->_buildCondition($sql, $field);

        return $sql->count();
    }

    public function searchRecord($field, $orderby, $limit, $offset)
    {
        $sql = self::whereRaw('1 = 1');

        $sql = $this->_buildCondition($sql, $field);

        $sql = $this->_buildOrderby($sql, $orderby);

        return $sql->paginate($limit);
    }

    protected function _buildCondition($sql, $field)
    {
        foreach ($field as $key => $value) {
            switch ($key) {
                case 'fid':
                    $sql = $sql->where('fid', $value);
                    break;
                case 'created_userid':
                    $sql = $sql->whereHas(PwThread::class, function ($query) use ($value) {
                        $query->where('created_userid', $value);
                    });
                    break;
                case 'title_keyword':
                    $sql = $sql->whereHas(PwThread::class, function ($query) use ($value) {
                        $query->where('subject', 'like', "%$value%");
                    });
                    break;
                case 'created_time_start':
                    $sql = $sql->whereHas(PwThread::class, function ($query) use ($value) {
                        $query->where('created_time', '>', $value);
                    });
                    break;
                case 'created_time_end':
                    $sql = $sql->whereHas(PwThread::class, function ($query) use ($value) {
                        $query->where('created_time', '<', $value);
                    });
                    break;
                case 'operator':
                    $sql = $sql->where('operate_username', $value);
                    break;
                case 'operate_time_start':
                    $sql = $sql->where('operate_time', '>', $value);
                    break;
                case 'operate_time_end':
                    $sql = $sql->where('operate_time', '<', $value);
                    break;
            }
        }
        return $sql;
    }

    protected function _buildOrderby($sql, $orderby)
    {
        foreach ($orderby as $key => $value) {
            switch ($key) {
                case 'tid':
                    $sql = $sql->orderby('tid', ($value ? 'ASC' : 'DESC'));
                    break;
            }
        }
        return $sql;
    }
}