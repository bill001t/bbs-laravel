<?php

namespace App\Services\usertag\ds\dao;

use App\Services\usertag\ds\relation\PwUserTag;
use App\Core\Hook\SimpleHook;

class PwUserTagDao extends PwUserTag
{
    /**
     * 根据标签ID获得该标签信息
     *
     * @param int $tag_id
     * @return array
     */
    public function getTag($tag_id)
    {
        return self::find($tag_id);
    }

    /**
     * 根据标签ID列表批量获取标签信息
     *
     * @param array $tag_ids
     * @return array
     */
    public function fetchTag($tag_ids)
    {
        return self::whereIn('tag_id', $tag_ids)
            ->get();
    }

    /**
     * 根据标签名字获取标签
     *
     * @param string $name
     * @return array
     */
    public function getTagByName($name)
    {
        return self::where('name', $name)
            ->get();
    }

    /**
     * 获得热门标签
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function getHotTag($limit, $start = 0)
    {
        return self::whereIn('ifhot', 1)
            ->paginate($limit);
    }

    /**
     * 统计热门标签
     *
     * @return int
     */
    public function countHotTag()
    {
        return self::whereIn('ifhot', 1)
            ->count();
    }

    /**
     * 添加标签
     *
     * @param array $data
     * @return int
     */
    public function addTag($data)
    {
        return self::create($data);
    }

    /**
     * 批量添加标签
     *
     * @param array $data
     * @return int
     */
    public function batchAddTag($data)
    {
        $clear = array();
        foreach ($data as $_item) {
            $clear[] = array($_item['name'], $_item['ifhot']);
            self::create($clear);
        }

        return true;
    }

    /**
     * 修改标签
     *
     * @param int $tag_id 标签ID
     * @param array $data 标签数据
     * @return int
     */
    public function updateTag($tag_id, $data, $incrementData)
    {
        foreach($incrementData as $k => $v){
            self::where('tag_id', $tag_id)
                ->increment($k, $v);
        }

        return self::where('tag_id', $tag_id)
            ->update($data);
    }

    /**
     * 批量修改标签
     *
     * @param array $tag_ids
     * @param int $ifhot
     * @return boolean
     */
    public function batchUpdateTag($tag_ids, $ifhot)
    {
        return self::whereIn('tag_id', $tag_ids)
            ->update(['ifhot' => $ifhot]);
    }

    /**
     * 根据标签ID删除标签
     *
     * @param int $tag_id
     * @return int
     */
    public function deleteTag($tag_id)
    {
        SimpleHook::getInstance('PwUserTagDao_deleteTag')->runDo($tag_id);
        return self::destroy($tag_id);
    }

    /**
     * 批量删除标签
     *
     * @param array $tag_ids
     * @return boolean
     */
    public function batchDeleteTag($tag_ids)
    {
        SimpleHook::getInstance('PwUserTagDao_batchDeleteTag')->runDo($tag_ids);
        return self::destroy($tag_ids);
    }


    /**
     * 根据条件搜索标签
     *
     * @param array $condition
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function searchTag($condition, $limit, $start = 0)
    {
        $sql = self::whereRaw('1 = 1');

        $sql = $this->_buildConditions($sql, $condition);

        return $sql->paginate($limit);
    }

    /**
     * 根据条件统计标签
     *
     * @param array $condition
     * @return int
     */
    public function countSearchTag($condition)
    {
        $sql = self::whereRaw('1 = 1');

        $sql = $this->_buildConditions($sql, $condition);

        return $sql->count();
    }

    /**
     * 标签搜索
     *  TODO
     * @param array $condition
     * @return array
     */
    private function _buildConditions($sql, $condition)
    {
        foreach ($condition as $key => $val) {
            if ($val !== 0 && !$val) continue;
            switch ($key) {
                case 'name':
                    $sql = $sql->where('name', 'like', $val . '%');
                    break;
                case 'ifhot':
                    $sql = $sql->where('ifhot', $val);
                    break;
                case 'min_count':
                    $sql = $sql->where('used_count', '>=', $val);
                    break;
                case 'max_count':
                    $sql = $sql->where('used_count', '<=', $val);
                    break;
                default:
                    break;
            }
        }
        return $sql;
    }
}