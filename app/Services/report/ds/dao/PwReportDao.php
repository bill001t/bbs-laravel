<?php

namespace App\Services\report\ds\dao;

use App\Services\report\ds\relation\PwReport;

/**
 * 举报DAO
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 * @package wind
 */
class PwReportDao extends PwReport
{
    /**
     * 添加单条消息
     *
     * @param array $fields
     * @return bool
     */
    public function add($fields)
    {
        return self::create($fields);
    }

    /**
     * 删除单条
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
     * 更新单条
     *
     * @param int $id
     * @param array $fields
     * @return bool
     */
    public function _update($id, $fields)
    {
        return self::where('id', $id)
            ->update($fields);
    }

    /**
     * 批量更新
     *
     * @param array $ids
     * @param array $fields
     * @return bool
     */
    public function batchUpdate($ids, $fields)
    {
        return self::whereIn('id', $ids)
            ->update($fields);
    }

    /**
     * 取一条
     *
     * @param int $id
     * @return array
     */
    public function get($id)
    {
        return self::find($id);
    }

    /**
     * 批量取
     *
     * @param array $ids
     * @return array
     */
    public function fetch($ids)
    {
        return self::whereIn('id', $ids)
            ->get();
    }

    /**
     * 根据举报来源和是否处理统计数量
     *
     * @param int $type
     * @param int $ifcheck
     * @return array
     */
    public function countByType($ifcheck, $type)
    {
        $sql = self::where('ifcheck', $ifcheck);

        if ($type) {
            $sql = $sql->where('type', $type);
        }

        return $sql->count();
    }

    /**
     * 根据举报来源和是否处理取列表
     *
     * @param int $type
     * @param int $ifcheck
     * @return array
     */
    public function getListByType($ifcheck, $type, $limit, $start)
    {
        $sql = self::where('ifcheck', $ifcheck);

        if ($type) {
            $sql = $sql->where('type', $type);
        }

        return $sql->orderby('created_time', 'desc')
            ->paginate($limit);
    }
}