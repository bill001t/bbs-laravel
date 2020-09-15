<?php

namespace App\Services\link\ds\dao;

use App\Services\link\ds\relation\PwLink;

class PwLinkDao extends PwLink
{

    /**
     * 添加一条链接
     *
     * @param array $data
     * @return int
     */
    public function addLink($data)
    {
        return self::create($data);
    }

    /**
     * 删除一条链接
     *
     * @param int $lid
     * @return bool
     */
    public function _delete($lid)
    {
        return self::destroy($lid);
    }

    /**
     * 删除多条信息
     *
     * @param array $lids
     * @return bool
     */
    public function batchDelete($lids)
    {
        return self::destroy($lids);
    }

    /**
     * 修改一条信息
     *
     * @param int $lid
     * @param array $data
     * @return bool
     */
    public function updateLink($lid, $data)
    {
        return self::where('lid', $lid)
            ->update($data);
    }

    /**
     * 获取一条信息
     *
     * @param int $lid
     * @return array
     */
    public function getLink($lid)
    {
        return self::find($lid);
    }

    /**
     * 获取链接数量
     *
     * @param int $ifcheck 0 未审核| 1已审核
     * @return array
     */
    public function countLinks($ifcheck)
    {
        $sql = self::whereRaw('1 = 1');

        if ($ifcheck !== '') {
            $sql = $sql->where('ifcheck', $ifcheck);
        }

        return $sql->count();
    }

    /**
     * 获取链接
     *
     * @param int $ifcheck 0 未审核| 1已审核
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function getLinks($start, $limit, $ifcheck)
    {
        $sql = self::whereRaw('1 = 1');

        if ($ifcheck !== '') {
            $sql = $sql->where('ifcheck', $ifcheck);
        }

        return $sql->orderby('vieworder', 'asc')
            ->paginate($limit);

    }

    /**
     * 根据lids获取链接
     *
     * @param array $lids
     * @return array
     */
    public function getLinksByLids($lids)
    {
        $sql = self::where('ifcheck', 1);

        if ($lids) {
            $sql = $sql->whereIn('lid', $lids);
        }

        return $sql->orderby('vieworder', 'asc')
            ->get();
    }
}