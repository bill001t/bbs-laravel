<?php

namespace App\Services\Api\user\ds\dao;

use DB;
use Illuminate\Database\Eloquent\Model;

class WindidUserSearchDao extends Model
{

    protected $_table = '_user';
    protected $_dataTable = '_user_data';
    protected $_infoTable = '_user_info';

    /**
     * 根据查询条件查询用户数据
     *
     * @param array $condition
     * @param int $limit
     * @param int $start
     * @param array $orderby
     * @return array
     */
    public function searchUser($condition, $limit, $start, $orderby)
    {
        $sql = DB::table($this->_table . ' as u')
            ->select(DB::raw('u.*'));

        if (array_intersect(['gender', 'location', 'hometown'], $condition)) {
            $sql = $sql->leftJoin($this->_infoTable . ' as i', 'u.uid', '=', 'i.uid');
        }

        $sql = $this->_buildCondition($sql, $condition);

        $sql = $this->_buildOrderby($sql, $orderby);

        return $sql->paginate($limit);
    }

    /**
     * 根据查询条件统计
     *
     * @param array $condition
     * @return int
     */
    public function countSearchUser($condition)
    {
        $sql = DB::table($this->_table . ' as u');

        return $sql = $this->_buildCondition($sql, $condition)
            ->count();
    }

    /**
     * 总是获取相关三张表的所有数据
     * 门户数据获取
     *
     * @param array $condition
     * @param int $limit
     * @param int $start
     * @param array $orderby
     */
    public function searchUserAllData($condition, $limit, $start, $orderby)
    {
        $sql = DB::table($this->_table . ' as u')
            ->leftJoin($this->_dataTable . ' as d', 'u.uid', '=', 'd.uid')
            ->leftJoin($this->_infoTable . ' as i', 'u.uid', '=', 'i.uid')
            ->select(DB::raw('u.*, d.*, i.*'));

        $sql = $this->_buildCondition($sql, $condition);

        $sql = $this->_buildOrderby($sql, $orderby);

        return $sql->paginate($limit);
    }

    /**
     * 组装查询信息
     *
     * @param array $condition
     * @return string
     */
    private function _buildCondition($sql, $condition)
    {
        if (!$condition) return $sql;

        foreach ($condition as $k => $v) {
            if ($v != 0 && !$v) continue;
            switch ($k) {
                case 'username':
                    $sql = $sql->where('u.username', 'LIKE', $v . '%');
                    break;
                case 'uid':
                    if (is_array($v)) {
                        $sql = $sql->whereIn('u.uid', $v);
                    } else {
                        $sql = $sql->where('u.uid', $v);
                    }
                    break;
                case 'email':
                    $sql = $sql->where('u.email', 'LIKE', $v . '%');
                    break;
                case 'regdate':
                    $sql = $sql->where('u.regdate', '>=', $v);
                    break;
                case 'gender':
                    $sql = $sql->where('i.gender', '>=', $v);
                    break;
                case 'location':
                    $sql = $sql->where('i..location', $v);
                    break;
                case 'hometown':
                    $sql = $sql->where('i..hometown', $v);
                    break;
                default:
                    break;
            }
        }

        return $sql;
    }

    /**
     * 构建orderBy
     *
     * @param array $orderby
     * @return array
     */
    protected function _buildOrderby($sql, $orderby)
    {
        return $sql;
    }
}