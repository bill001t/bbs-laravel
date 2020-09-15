<?php

namespace App\Services\invite\ds\dao;

use App\Services\invite\ds\relation\InviteCode;

class PwInviteCodeDao extends InviteCode
{
    /**
     * 根据邀请码获取该条邀请码信息
     *
     * @param string $code
     * @return array
     */
    public function getCode($code)
    {
        return self::find($code);
    }

    /**
     * 根据创建用户ID获得该用户邀请成功的邀请用户
     *
     * @param int $uid
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function getUsedCodeByCreatedUid($uid, $limit = 18, $start = 0)
    {
        return self::where('created_userid', $uid)
            ->where('ifused', 1)
            ->orderby('modified_time', 'desc')
            ->paginate($limit);
    }

    /**
     * 根据用户ID统计该用户邀请的人
     *
     * @param int $uid
     * @return int
     */
    public function countUsedCodeByCreatedUid($uid)
    {
        return self::where('created_userid', $uid)
            ->where('ifused', 1)
            ->count();
    }

    /**
     * 根据条件获得该用户的邀请码信息
     *
     * @param array $condition 查询条件
     * @param int $limit 查询条数
     * @param int $offset 开始查询的位置
     * @return array
     */
    public function searchCode($condition, $limit, $offset)
    {
        $sql = self::whereRaw('1 = 1');

        $sql = $this->_buildCondition($sql, $condition);

        return $sql->orderby('created_time', 'desc')
            ->paginate($limit);
    }

    /**
     * 根据查询条件获取信息
     *
     * @param array $condition
     * @return int
     */
    public function countSearchCode($condition)
    {
        $sql = self::whereRaw('1 = 1');

        $sql = $this->_buildCondition($sql, $condition);

        return $sql->count();
    }

    /**
     * 根据用户ID及时间统计大于这个时间的用户购买的邀请码数量
     *
     * @param int $uid
     * @param int $time
     * @return int
     */
    public function countByUidAndTime($uid, $time)
    {
        return self::where('created_userid', $uid)
            ->where('created_time', '>', $time)
            ->count();
    }

    /**
     * 批量检查该code是否存在，并返回存在的codes
     *
     * @param array $codes
     * @return array
     */
    public function fetchCode($codes)
    {
        return self::whereIn('code', $codes)
            ->get();
    }

    /**
     * 添加邀请码
     *
     * @param array $data
     * @return
     */
    public function addCode($data)
    {
        return self::create($data);
    }

    /**
     * 批量添加邀请码
     *
     * @param array $data
     * @return boolean
     */
    public function batchAddCode($data)
    {
        $clear = array();
        foreach ($data as $_item) {
            $_temp = array();
            $_temp['code'] = $_item['code'];
            $_temp['created_userid'] = $_item['created_userid'];
            $_temp['created_time'] = $_item['created_time'];
            $clear[] = $_temp;
        }
        if (!$clear) return false;

        return self::create($clear);
    }

    /**
     * 更新
     *
     * @param string $code
     * @param array $data
     */
    public function updateCode($code, $data)
    {
        return self::where('code', $code)
            ->update($data);
    }

    /**
     * 根据邀请码删除信息
     *
     * @param string $code
     * @return boolean
     */
    public function deleteCode($code)
    {
        return self::destroy($code);
    }

    /**
     * 批量删除邀请码信息
     *
     * @param array $codes
     * @return boolean
     */
    public function batchDeleteCode($codes)
    {
        return self::destroy($codes);
    }

    /**
     * 构建查询条件
     *
     * @param array $condition
     * @return array
     */
    private function _buildCondition($sql, $condition)
    {
        foreach ($condition as $k => $v) {
            switch ($k) {
                case 'ifused':
                    if (in_array($v, array(0, 1))) {
                        $sql = $sql->where('ifused', $v);
                    }
                    break;
                case 'expire':
                    $sql = $sql->where('created_time', '>', $v);
                    break;
                case 'created_userid':
                    $sql = $sql->where('created_userid', $v);
                    break;
                case 'invited_userid':
                    $sql = $sql->where('invited_userid', $v);
                    break;
                default:
                    break;
            }
        }
        return $sql;
    }
}