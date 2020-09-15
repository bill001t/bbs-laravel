<?php

namespace App\Services\Api\school\ds\dao;

use App\Services\Api\school\ds\relation\School;

class SchoolDao extends School
{
    /**
     * 获取学校的详细信息
     *
     * @param int $schoolid
     * @return array
     */
    public function getSchool($schoolid)
    {
        return self::find($schoolid);
    }

    /**
     * 根据学校ID列表批量获取学校信息
     *
     * @param array $schoolids
     * @return array
     */
    public function fetchSchool($schoolids)
    {
        return self::whereIn('schoolid', $schoolids)
            ->get();
    }

    /**
     * 根据地区获得学校列表
     *
     * @param int $areaid
     * @return array
     */
    public function getSchoolByAreaidAndTypeid($areaid, $typeid = 3)
    {
        return self::where('areaid', $areaid)
            ->where('typeid', $typeid)
            ->orderby('first_char')
            ->get();
    }

    /**
     * 添加一个学校
     *
     * @param array $data
     * @return int
     */
    public function addSchool($data)
    {
        return self::create($data);
    }

    /**
     * 批量添加学校
     *
     * @param array $data
     * @return int
     */
    public function batchAddSchool($data)
    {
        $clear = array();
        foreach ($data as $_item) {
            $clear[] = array($_item['name'], $_item['areaid'], $_item['first_char'], $_item['typeid']);
        }
        if (!$clear) return false;

        return self::create($clear);
    }

    /**
     * 更新学校
     *
     * @param int $schoolid
     * @param array $data
     */
    public function updateSchool($schoolid, $data)
    {
        return self::where('schoolid', $schoolid)
            ->update($data);
    }

    /**
     * 删除学校
     *
     * @param int $schoolid
     * @return int
     */
    public function deleteSchool($schoolid)
    {
        return self::destroy($schoolid);
    }

    /**
     * 批量删除学校
     *
     * @param array $schoolids
     * @return int
     */
    public function batchDeleteSchool($schoolids)
    {
        return self::destroy($schoolids);
    }

    /**
     * 根据学校名搜索学校
     *
     * @param array $condition
     * @return array
     */
    public function searchSchool($condition, $limit, $start)
    {
        $sql = self::whereRaw('1=1');

        $sql = $this->_buildCondition($sql, $condition);

        return $sql->orderby('first_char')
            ->paginate($limit);
    }

    /**
     * 统计数据
     *
     * @param array $condition
     * @return int
     */
    public function countSearchSchool($condition)
    {
        $sql = self::whereRaw('1=1');

        $sql = $this->_buildCondition($sql, $condition);

        return $sql->count();
    }

    /**
     * 构建查询条件
     *
     * @param array $conditions
     * @return array
     */
    private function _buildCondition($sql, $conditions)
    {
        foreach ($conditions as $_key => $_var) {
            if (!$_var) continue;
            switch ($_key) {
                case 'name':
                    $sql = $sql->where('name', 'like', $_var . '%');
                    break;
                case 'typeid':
                    $sql = $sql->where('typeid', $_var);
                    break;
                case 'areaid':
                    $sql = $sql->where('areaid', $_var);
                    break;
                case 'first_char':
                    $sql = $sql->where('first_char', $_var);
                    break;
                default:
                    break;
            }
        }
        return $sql;
    }
}