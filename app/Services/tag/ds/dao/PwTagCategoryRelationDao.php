<?php

namespace App\Services\tag\ds\dao;

use App\Services\tag\ds\relation\PwTagCategoryRelation;
use DB;

class PwTagCategoryRelationDao extends PwTagCategoryRelation
{
    /**
     * 添加
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
                $v['category_id']
            );
        }

        return self::firstOrCreate($array);
    }

    /**
     * 根据category_id删除
     *
     * @param int $categoryId
     * @return bool
     */
    public function deleteByCategoryId($categoryId)
    {
        return self::destroy($categoryId);
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
     * 根据tag_ids删除
     *
     * @param array $tagIds
     * @return bool
     */
    public function deleteByTagIds($tagIds)
    {
        return self::destroy($tagIds);
    }

    /**
     * 根据category_id获取数据
     *
     * @param int $categoryId
     * @param int $num
     * @return array
     */
    public function getByCategoryId($categoryId, $num)
    {
        return self::where('category_id', $categoryId)
            ->paginate($num);
    }

    /**
     * 统计分类话题数
     *
     * @return array
     */
    public function countByCategoryId()
    {
        return self::gouprby('category_id')
            ->select(DB::raw('COUNT(*) as count,category_id'))
            ->get();
    }

    /**
     * 根据$tagId获取数据
     *
     * @param int $tagId
     * @return array
     */
    public function getByTagId($tagId)
    {
        return self::where('tag_id', $tagId)
            ->get();
    }

    /**
     * 根据分类id　及　tag_ids获取数据
     *
     * @param array $tagIds
     * @param int $categoryId
     * @return array
     */
    public function getByCategoryAndTagIds($tagIds, $categoryId)
    {
        return self::whereIn('tag_id', $tagIds)
            ->where('category_id', $categoryId)
            ->get();
    }

    /**
     * 根据tag_ids获取数据
     *
     * @param array $tagIds
     */
    public function getByTagIds($tagIds)
    {
        return self::whereIn('tag_id', $tagIds)
            ->get();
    }
}