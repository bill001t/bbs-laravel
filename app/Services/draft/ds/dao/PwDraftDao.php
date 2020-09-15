<?php

namespace App\Services\draft\ds\dao;

use App\Services\draft\ds\relation\Draft;

/**
 * 草稿DAO
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 * @package wind
 */
class PwDraftDao extends Draft
{
    /**
     * 添加
     *
     * @param array $data
     * @return bool
     */
    public function add($data)
    {
        return self::create($data);
    }

    /**
     * 删除
     *
     * @param int $id
     * @return bool
     */
    public function _delete($id)
    {
        return self::destroy($id);
    }

    /**
     * 批量删除
     *
     * @param array $ids
     * @return bool
     */
    public function batchDelete($ids)
    {
        return self::destroy($ids);
    }

    /**
     * 修改
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function _update($id, $data)
    {
        return self::where('id', $id)
            ->update($data);
    }

    /**
     * 获取一条信息
     *
     * @param int $id
     * @return array
     */
    public function get($id)
    {
        return self::find($id);
    }

    /**
     * 根据用户统计草稿箱数量
     *
     * @param int $uid
     * @return int
     */
    public function countByUid($uid)
    {
        return self::where('created_userid', $uid)
            ->count();
    }

    /**
     * 根据用户获取$num条数据
     *
     * @param int $uid
     * @param int $num
     * @return array
     */
    public function getByUid($uid, $num)
    {
        return self::where('created_userid', $uid)
            ->orderby('id', 'desc')
            ->paginate($num);
    }
}