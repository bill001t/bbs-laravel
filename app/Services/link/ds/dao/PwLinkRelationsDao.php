<?php

namespace App\Services\link\ds\dao;

use App\Services\link\ds\relation\PwLinkRelation;
use DB;

class PwLinkRelationsDao extends PwLinkRelation
{
    /**
     * 添加
     *
     * @param array $data
     * @return int
     */
    public function addLinkRelations($data)
    {
        return self::create($data);
    }

    /**
     * 根据lid删除
     *
     * @param int $lid
     * @return bool
     */
    public function delRelationsByLid($lid)
    {
        return self::destroy($lid);
    }

    /**
     * 根据lid批量删除
     *
     * @param int $lid
     * @return bool
     */
    public function batchDelRelationsByLid($lids)
    {
        return self::destroy($lids);
    }

    /**
     * 根据typeid删除
     *
     * @param int $typeid
     * @return bool
     */
    public function delRelationsByTypeid($typeid)
    {
        return self::where('typeid', $typeid)
            ->delete();
    }

    /**
     * 根据typeid获取数据
     *
     * @param int $typeid
     * @return int
     */
    public function getByTypeId($typeid)
    {
        $sql = self::whereRaw('1 = 1');

        if ($typeid != '') {
            $sql = $sql->where('typeid', $typeid);
        }

        return $sql->get();
    }

    /**
     * 根据lid获取数据
     *
     * @param int $lid
     * @return int
     */
    public function getByLinkId($lid)
    {
        self::find($lid);
    }

    /**
     * 根据链接ID批量获取与类型的对于关系
     *
     * @param array $linkids
     * @return array
     */
    public function fetchByLinkId($linkids)
    {
        return self::whereIn('lid', $linkids)
            ->get();
    }

    /**
     * 统计分类数量
     *
     * @return array
     */
    public function countLinkTypes()
    {
        return self::select(DB::Raw('typeid,COUNT(*) as linknum'))
            ->groupby('typeid');
    }
}