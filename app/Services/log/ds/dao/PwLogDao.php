<?php

namespace App\Services\log\ds\dao;

use App\Services\log\ds\relation\log;

class PwLogDao extends log
{
    /**
     * 根据tid获得该帖子的相关管理日志
     *
     * @param int $tid
     * @param int $pid
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function getLogByTid($tid, $pid, $limit, $start = 0)
    {
        return self::where('tid', $tid)
            ->where('pid', $pid)
            ->orderby('id', 'desc')
            ->paginate($limit);
    }

    public function fetchLogByTid($tids, $typeid)
    {
        return self::whereIn('tid', $tids)
            ->where('pid', 0)
            ->whereIn('typeid', $typeid)
            ->get();
    }

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
            $_temp['created_userid'] = $_item['created_userid'];
            $_temp['created_username'] = $_item['created_username'];
            $_temp['operated_uid'] = $_item['operated_uid'];
            $_temp['operated_username'] = $_item['operated_username'];
            $_temp['created_time'] = $_item['created_time'];
            $_temp['typeid'] = $_item['typeid'];
            $_temp['fid'] = $_item['fid'];
            $_temp['tid'] = $_item['tid'];
            $_temp['ip'] = $_item['ip'];
            $_temp['extends'] = $_item['extends'];
            $_temp['content'] = $_item['content'];
            $_temp['pid'] = $_item['pid'];
            $clear[] = $_temp;
        }
        if (!$clear) return false;

        return self::create($clear);
    }

    /**
     * 根据日志ID删除某条日志
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
        $sql = $this->_buildCondition($sql, $condition);

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
        $sql = $this->_buildCondition($sql, $condition);

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
                case 'operated_username':
                case 'created_username':
                case 'ip':
                    $sql = $sql->where($_k, 'like', $_v . '%');
                    break;
                case 'operated_uid':
                case 'created_userid':
                    if (!is_array($_v)) $_v = array($_v);
                    $sql = $sql->whereIn($_k, $_v);
                    break;
                case 'typeid':
                case 'fid':
                    $sql = $sql->where($_k, $_v);
                    break;
                case 'start_time':
                    $sql = $sql->where($_k, '>=', $_v);
                    break;
                case 'end_time':
                    $sql = $sql->where($_k, '<=', $_v);
                    break;
                case 'keywords':
                    $sql = $sql->where($_k, 'like', '%' . $_v . '%');
                    break;
                default:
                    break;
            }
        }
        return $sql;
    }
}