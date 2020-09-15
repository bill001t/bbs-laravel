<?php

namespace App\Services\tag\ds\dao;

use App\Services\tag\ds\relation\PwTagCategory;

class PwTagCategoryDao extends PwTagCategory
{
    /**
     * 添加一条分类
     *
     * @param array $data
     * @return int
     */
    public function addTagCategory($data)
    {
        return self::create($data);
    }

    /**
     * 删除一条分类
     *
     * @param int $categoryId
     * @return bool
     */
    public function _delete($categoryId)
    {
        return self::destroy($categoryId);
    }

    /**
     * 修改一条分类
     *
     * @param int $categoryId
     * @param array $data
     * @return bool
     */
    public function _update($categoryId, $data)
    {
        return self::where('category_id', $categoryId)
            ->update($data);
    }

    /**
     * 批量添加分类
     *
     * @param array $data
     * @return int
     */
    public function addCategorys($data)
    {
        $array = array();
        foreach ($data as $v) {
            $array[] = array(
                $v['category_name'],
                $v['alias'],
                $v['vieworder']
            );
        }
        if (!is_array($array) || !count($array)) {
            return false;
        }

        return self::create($array);
    }

    /**
     * 修改多条分类
     *
     * @param array $data
     * @return int
     */
    public function updateCategorys($data)
    {
        $array = array();
        foreach ($data as $v) {
            $array[] = array(
                $v['category_id'],
                $v['category_name'],
                $v['alias'],
                $v['vieworder']
            );
        }
        if (!is_array($array) || !count($array)) {
            return false;
        }

        return self::firstOrCreate($array);
    }

    /**
     * 根据category_id获取话题分类
     *
     * @param int $id
     * @return int
     */
    public function get($id)
    {
        return self::find($id);
    }

    /**
     * 获取所有话题分类
     *
     * @return int
     */
    public function getAllCategorys()
    {
        return self::all()
            ->orderby('vieworder', 'asc');
    }

    /**
     * 获取所有话题分类
     *
     * @return int
     */
    public function fetchCategories($ids)
    {
        return self::whereIn('category_id', $ids)
            ->orderby('vieworder', 'asc')
            ->get();
    }


}