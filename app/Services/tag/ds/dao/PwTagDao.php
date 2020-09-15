<?php

namespace App\Services\tag\ds\dao;

use App\Services\tag\ds\relation\PwTag;
use App\Services\tag\ds\relation\PwTagRelation;
use App\Services\tag\ds\relation\PwTagAttention;
use App\Services\tag\ds\relation\PwTagCategoryRelation;

class PwTagDao extends PwTag
{
    /**
     * 添加一条话题
     *
     * @param array $data
     * @return int
     */
    public function addTag($data)
    {
        return self::create($data);
    }

    /**
     * 删除一条话题
     *
     * @param int $tagId
     * @return bool
     */
    public function _delete($tagId)
    {
        return self::destroy($tagId);
    }

    /**
     * 批量删除话题
     *
     * @param array $tagIds
     * @return bool
     */
    public function batchDelete($tagIds)
    {
        return self::destroy($tagIds);
    }

    /**
     * 修改一条话题
     *
     * @param int $tagId
     * @param array $data
     * @param array $increaseData
     * @return bool
     */
    public function _update($tagId, $data = array(), $increaseData = array())
    {
        foreach($increaseData as $k => $v){
            self::where('tag_id', $tagId)
                ->increment($k, $v);
        }

        return self::where('tag_id', $tagId)
            ->update($data);
    }

    /**
     * 批量修改话题
     *
     * @param array $tagIds
     * @param array $data
     * @param array $increaseData
     * @return bool
     */
    public function batchUpdate($tagIds, $fields, $increaseFields = array())
    {
        foreach($increaseFields as $k => $v){
            self::whereIn('tag_id', $tagIds)
                ->increment($k, $v);
        }

        return self::whereIn('tag_id', $tagIds)
            ->update($fields);
    }

    /**
     * 获取一条话题
     *
     * @param int $tagId
     * @return array
     */
    public function getTag($tagId)
    {
        return self::find($tagId);
    }

    /**
     * 批量获取话题
     *
     * @param array $tagIds
     * @return array
     */
    public function fetchTag($tagIds)
    {
        return self::whereIn('tag_id', $tagIds)
            ->get();
    }

    /**
     * 根据归属话题获取话题
     *
     * @param int $parentTagId
     * @return array
     */
    public function getTagByParent($parentTagId)
    {
        return self::where('parent_tag_id', $parentTagId)
            ->get();
    }

    /**
     * 根据话题名称获取一条话题
     *
     * @param string $tagName
     * @return array
     */
    public function getTagByName($tagName)
    {
        return self::where('tag_name', $tagName)
            ->get();
    }

    /**
     * 根据话题名称批量获取话题
     *
     * @param array $tagNames
     * @return array
     */
    public function getTagsByNames($tagNames)
    {
        return self::whereIn('tag_name', $tagNames)
            ->get();
    }

    /**
     * 搜索话题count -- 只供后台搜索使用
     *
     * @param string $name
     * @param int $ifHot
     * @param int $categoryId
     * @param int $attentionCountStart
     * @param int $attentionCountEnd
     * @param int $contentCountStart
     * @param int $contentCountEnd
     * @return int
     */
    public function countTagByCondition($name, $ifHot, $categoryId, $attentionCountStart, $attentionCountEnd, $contentCountStart, $contentCountEnd)
    {
        $sql = self::whereRaw('1 = 1');

        if ($name) {
            $sql = $sql->where('tag_name', 'like', '%' . $name . '%');
        }
        if ($ifHot >= 0) {
            $sql = $sql->where('ifhot', $ifHot);
        }
        if ($categoryId) {
            $sql = $sql->whereHas(PwTagCategoryRelation::class, function($query) use($categoryId){
                $query->where('category_id', $categoryId);
            });
        }
        if ($attentionCountStart != '') {
            $sql = $sql->where('attention_count', '>', $attentionCountStart);
        }
        if ($attentionCountEnd != '') {
            $sql = $sql->where('attention_count', '<=', $attentionCountEnd);
        }
        if ($contentCountStart != '') {
            $sql = $sql->where('content_count', '>=', $contentCountStart);
        }
        if ($contentCountEnd != '') {
            $sql = $sql->where('content_count', '<=', $contentCountStart);
        }

        return $sql->count();
    }

    /**
     * 搜索话题列表 -- 只供后台搜索使用
     *
     * @param int $start
     * @param int $limit
     * @param string $name
     * @param int $ifHot
     * @param int $categoryId
     * @param int $attentionCountStart
     * @param int $attentionCountEnd
     * @param int $contentCountStart
     * @param int $contentCountEnd
     * @return array
     */
    public function getTagByCondition($start, $limit, $name, $ifHot, $categoryId, $attentionCountStart, $attentionCountEnd, $contentCountStart, $contentCountEnd)
    {
        $sql = self::whereRaw('1 = 1');

        if ($name) {
            $sql = $sql->where('tag_name', 'like', '%' . $name . '%');
        }
        if ($ifHot >= 0) {
            $sql = $sql->where('ifhot', $ifHot);
        }
        if ($categoryId) {
            $sql = $sql->whereHas(PwTagCategoryRelation::class, function($query) use($categoryId){
                $query->where('category_id', $categoryId);
            });
        }
        if ($attentionCountStart != '') {
            $sql = $sql->where('attention_count', '>', $attentionCountStart);
        }
        if ($attentionCountEnd != '') {
            $sql = $sql->where('attention_count', '<=', $attentionCountEnd);
        }
        if ($contentCountStart != '') {
            $sql = $sql->where('content_count', '>=', $contentCountStart);
        }
        if ($contentCountEnd != '') {
            $sql = $sql->where('content_count', '<=', $contentCountStart);
        }

        return $sql->orderby('tag_id', 'desc')
            ->paginate($limit);
    }

    /**
     * 获取我关注的话题
     *
     * @param int $uid
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function getAttentionTag($uid, $start, $limit)
    {
        return self::whereHas(PwTagAttention::class, function($query) use($uid){
            $query->where('uid', $uid);
        })->orderby('content_count', 'desc')
            ->paginate($limit);
    }

    /**
     * 根据参数获取相关话题
     *
     * @param int $typeId
     * @param array $paramIds
     * @return array
     */
    public function getTagsByParamIds($typeId, $paramIds)
    {
        return self::whereHas(PwTagRelation::class, function($query) use($typeId, $paramIds){
            $query->where('type_id', $typeId)
            ->whereIn('param_id', $paramIds);
        })->orderby('content_count', 'desc')
            ->get();
    }
}