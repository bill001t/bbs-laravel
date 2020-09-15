<?php

namespace App\Services\tag\ds\dao;

use App\Services\tag\ds\relation\PwTagRecord;
use DB;

class PwTagRecordDao extends PwTagRecord
{
    protected $_table_category_relation = '_tag_category_relation';

    /**
     * 添加
     *
     * @param int $tagId
     * @param int $updateTime
     */
    public function addTagRecord($data)
    {
        return self::create($data);
    }

    /**
     * 更新tag update表的tagid
     *
     * @param int $fromTagId
     * @param int $toTagId
     * @return bool
     */
    public function updateTagRecordByTagId($fromTagId, $toTagId)
    {
        return self::where('tag_id', $fromTagId)
            ->update(['tag_id' => $toTagId]);
    }

    /**
     * 批量添加
     *
     * @param array $data
     * @return int
     */
    public function batchAddTagRecord($data)
    {
        $array = array();
        foreach ($data as $v) {
            $array[] = array(
                $v['tag_id'],
                intval($v['is_reply']),
                $v['update_time'],
            );
        }

        return self::firstOrCreate($array);
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
     * 根据时间删除
     *
     * @param int $updateTime
     * @return bool
     */
    public function deleteByTime($updateTime)
    {
        return self::where('update_time', '<', $updateTime)
            ->delete();
    }

    /**
     * 统计热门话题榜
     *
     * @param int $num
     * @return array
     */
    public function getHotTags($num)
    {
        return self::select(DB::raw('`tag_id`,COUNT(*) AS cnt'))
            ->groupby('tag_id')
            ->orderby('cnt', 'desc')
            ->take($num);
    }

    /**
     * 根据话题分类统计热门话题榜
     *
     * @param int $categoryId
     * @param int $num
     * @return array
     */
    public function getHotTagsByCategory($categoryId, $num)
    {
        return DB::select('SELECT * FROM (SELECT tag_id,COUNT(*) AS cnt FROM ' . $this->_table_category_relation . ' GROUP BY tag_id) AS t1 LEFT JOIN ' . $this->table . ' AS t2 WHERE t2.category_id =? ORDER BY t1.cnt DESC limit ?', [$categoryId, $num]);
    }
}