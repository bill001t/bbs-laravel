<?php

namespace App\Services\tag\ds\dao;

use App\Services\tag\ds\relation\PwTagRelation;
use DB;

class PwTagRelationDao extends PwTagRelation
{
    /**
     * 单个添加内容关系
     *
     * @param array $data
     * @return int
     */
    public function addRelation($data)
    {
        return self::create($data);
    }

    /**
     * 批量添加
     *
     * @param array $data
     * @return int
     */
    public function batchAddRelation($data)
    {
        $array = array();
        foreach ($data as $v) {
            $array[] = array(
                $v['tag_id'],
                $v['content_tag_id'],
                $v['type_id'],
                $v['param_id'],
                $v['created_time'],
            );
        }

        return self::firstOrCreate($array);
    }

    /**
     * 更新内容关系
     *
     * @param array $data
     * @return int
     */
    public function updateRelation($typeId, $paramId, $id, $data)
    {
        return self::where('type_id', $typeId)
            ->where('param_id', $paramId)
            ->where('content_tag_id', $id)
            ->update($data);
    }

    /**
     * 批量添加内容关系
     *
     * @param array $data
     * @return int
     */
    public function addRelations($data)
    {
        $array = array();
        foreach ($data as $v) {
            $array[] = array(
                $v['tag_id'],
                $v['content_tag_id'],
                $v['type_id'],
                $v['param_id'],
                $v['ifcheck'],
                $v['created_time'],
            );
        }

        return self::firstOrCreate($array);
    }

    /**
     * 更新tag relation表的tagid,content id
     * @param int $fromTagId
     * @param int $toTagId
     * @return bool
     */
    public function updateTagRelationByTagId($fromTagId, $toTagId)
    {
        return self::where('tag_id', $fromTagId)
            ->update(['tag_id' => $toTagId]);
    }

    /**
     * 根据tag_id删除
     *
     * @param int $tagId
     * @return bool
     */
    public function deleteByTagId($tagId)
    {
        return self::destroy($tagId);
    }

    /**
     * 根据tag_ids批量删除
     *
     * @param array $tagIds
     * @return bool
     */
    public function deleteByTagIds($tagIds)
    {
        return self::destroy($tagIds);
    }

    /**
     * 根据类型和ID删除
     *
     * @param int $typeId
     * @param int $paramId
     * @return bool
     */
    public function deleteByTypeId($typeId, $paramId)
    {
        return self::where('type_id', $typeId)
            ->where('param_id', $paramId)
            ->delete();
    }

    /**
     * 根据type_id、param_id、content_tag_id删除一条
     *
     * @param int $typeId
     * @param int $paramId
     * @param int $tagId
     * @return bool
     */
    public function _delete($typeId, $paramId, $tagId)
    {
        $sql = self::where('type_id', $typeId);

        if ($paramId) {
            $sql = $sql->where('param_id', $paramId);
        }
        if ($tagId) {
            $sql = $sql->where('content_tag_id', $tagId);
        }

        return $sql->delete();
    }

    /**
     * 根据type_id、param_id、content_tag_ids批量删除
     *
     * @param int $typeId
     * @param int $paramId
     * @param array $tagIds
     * @return bool
     */
    public function batchDeleteRelationsByType($typeId, $paramId, $tagIds)
    {
        return self::where('type_id', $typeId)
            ->where('param_id', $paramId)
            ->whereIn('content_tag_id', $tagIds)
            ->delete();
    }

    /**
     * 根据type_id、param_ids批量删除
     *
     * @param int $typeId
     * @param array $paramIds
     * @return bool
     */
    public function batchDelete($typeId, $paramIds)
    {
        return self::where('type_id', $typeId)
            ->whereIn('param_id', $paramIds)
            ->delete();
    }

    /**
     * 根据类型和ID获取数据
     *
     * @param int $typeId
     * @param int $paramId
     * @return array
     */
    public function getByTypeId($typeId, $paramId)
    {
        return self::where('type_id', $typeId)
            ->where('param_id', $paramId)
            ->get();
    }

    /**
     * 根据类型和IDs批量获取数据
     *
     * @param int $typeId
     * @param array $paramIds
     * @return array
     */
    public function fetchByTypeIdAndParamIds($typeId, $paramIds)
    {
        return self::where('type_id', $typeId)
            ->whereIn('param_id', $paramIds)
            ->get();
    }

    /**
     * 根据类型和ID统计数据
     *
     * @param int $tagId
     * @param int $typeId
     * @param int $ifcheck
     * @return array
     */
    public function countByTagId($tagId, $typeId, $ifcheck)
    {
        $sql = self::where('type_id', $typeId)
            ->where('tag_id', $tagId);

        if ($ifcheck) {
            $sql = $sql->where('ifcheck', $ifcheck);
        }

        return $sql->count();
    }

    /**
     * 根据类型和ID获取数据
     *
     * @param int $tagId
     * @param int $typeId
     * @param int $ifcheck
     * @return array
     */
    public function getByTagId($tagId, $typeId, $ifcheck, $offset, $num = 4)
    {
        $sql = self::where('type_id', $typeId)
            ->where('tag_id', $tagId);

        if ($ifcheck) {
            $sql = $sql->where('ifcheck', $ifcheck);
        }

        return $sql->orderby('created_time', 'desc')
            ->paginate($num);
    }
}