<?php

namespace App\Services\credit\ds\dao;

use App\Core\BaseTrait;
use App\Services\credit\ds\relation\creditLog;

/**
 * 积分策略设置具体内容DAO
 */
class PwCreditLogDao extends creditLog
{
    use BaseTrait;

    public function countLogByUid($uid)
    {
        return self::where('created_userid', $uid)
            ->count();
    }

    public function getLogByUid($uid, $perpage)
    {
        return self::where('created_userid', $uid)
            ->orderby('created_time', 'desc')
            ->paginate($perpage);
    }

    public function countBySearch($field)
    {
        $sql = self::whereRaw('1=1');
        $sql = $this->_getWhere($sql, $field);

        return $sql->count();
    }

    public function searchLog($field, $perpage)
    {
        $sql = self::whereRaw('1=1');
        $sql = $this->_getWhere($sql, $field);
        $sql->orderby('id', 'desc');

        return $sql->paginate($perpage);
    }

    public function batchAdd($data)
    {
        return self::_batchAdd($data);
        /*$array = array();
        foreach ($data as $key => $value) {
            $array[] = array(
                $value['ctype'],
                $value['affect'],
                $value['logtype'],
                $value['descrip'],
                $value['created_userid'],
                $value['created_username'],
                $value['created_time']
            );
        }

        return self::create($data);*/
    }

    private function _getWhere($sql, $field)
    {
        foreach ($field as $key => $value) {
            switch ($key) {
                case 'ctype':
                    $sql->where('ctype', $value);
                    break;
                case 'created_userid':
                    $sql->where('created_userid', $value);
                    break;
                case 'created_time_start':
                    $sql->where('created_time', '>', $value);
                    break;
                case 'created_time_end':
                    $sql->where('created_time', '<', $value);
                    break;
                case 'award':
                    $sql->where('affect', ($value == 1 ? '>' : '<'), $value);
                    break;
            }
        }
        return $sql;
    }
}