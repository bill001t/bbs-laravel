<?php

namespace App\Services\log\ds\dao;

use App\Services\log\ds\relation\logLogin;

class PwLogLoginDao extends logLogin
{
    /**
     * 添加日志
     *
     * @param array $data
     * @return int
     */
    public function addLog($data)
    {
        return self::create($data);
    }

    /**
     * 批量添加日志
     *
     * @param array $datas
     * @return int
     */
    public function batchAddLog($datas)
    {
        $clear = $fields = array();
        foreach ($datas as $key => $_item) {
            $_temp = array();
            $_temp['uid'] = $_item['uid'];
            $_temp['username'] = $_item['username'];
            $_temp['typeid'] = $_item['typeid'];
            $_temp['created_time'] = $_item['created_time'];
            $_temp['ip'] = $_item['ip'];
            $clear[] = $_temp;
        }
        if (!$clear) return false;

        return self::create($clear);
    }

    /**
     * 根据日志ID删除某条日志
     *
     * @param int $id
     * @return int
     */
    public function deleteLog($id)
    {
        return self::destroy($id);
    }

    /**
     * 根据日志ID列表删除日志
     *
     * @param array $ids
     * @return int
     */
    public function batchDeleteLog($ids)
    {
        return self::destroy($ids);
    }

    /**
     * 清除某个时间段之前的日志
     *
     * @param int $time
     * @return int
     */
    public function clearLogBeforeDatetime($time)
    {
        return self::where('created_time', '<', $time)
            ->delete();
    }

    /**
     * 根据条件搜索日志
     *
     * @param array $condition
     * @param int $limit
     * @param int $offset
     */
    public function search($condition, $limit = 10, $offset = 0)
    {
        $sql = self::whereRaw('1 = 1');
        $sql =  $this->_buildCondition($sql, $condition);

        return $sql->orderby('id', 'desc')
            ->paginate($limit);
    }

    /**
     * 根据搜索条件统计结果
     *
     * @param array $condition
     * @return int
     */
    public function countSearch($condition)
    {
        $sql = self::whereRaw('1 = 1');
        $sql =  $this->_buildCondition($sql, $condition);

        return $sql->count();
    }

    /**
     * 后台搜索
     *
     * @param array $condition
     * @return array
     */
    private function _buildCondition($sql, $condition)
    {
        foreach ($condition as $_k => $_v) {
            if (!$_v) continue;
            switch ($_k) {
                case 'created_username':
                    $sql = $sql->where('username', 'like', $_v . '%');
                    break;
                case 'typeid':
                    $sql = $sql->where('typeid', $_v);
                    break;
                case 'ip':
                    $sql = $sql->where('ip', 'like', $_v . '%');
                    break;
                case 'start_time':
                    $sql = $sql->where('created_time', '>=', $_v);
                    break;
                case 'end_time':
                    $sql = $sql->where('created_time', '<=', $_v);
                    break;
                default:
                    break;
            }
        }
        return $sql;
    }
}