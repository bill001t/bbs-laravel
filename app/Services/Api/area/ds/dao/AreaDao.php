<?php

namespace App\Services\Api\area\ds\dao;

use App\Services\Api\area\ds\relation\Area;

class AreaDao extends Area
{
    /**
     * 根据上一级ID获得下一级的所有地区
     *
     * @param int $parentid
     * @return array
     */
    public function getAreaByParentid($parentid)
    {
        return self::where('parentid', $parentid)
            ->orderby('areaid')
            ->get();
    }

    /**
     * 根据地区ID获得该地区的相关信息
     *
     * @param int $areaid
     * @return array
     */
    public function getArea($areaid)
    {
        return self::find($areaid);
    }

    /**
     * 根据地区ID列表批量获取地区列表
     *
     * @param array $areaids
     * @return array
     */
    public function fetchByAreaid($areaids)
    {
        return self::whereIn('areaid', $areaids)
            ->get();
    }

    /**
     * 获取所有的地区
     *
     * @return array
     */
    public function fetchAll()
    {
        return self::all()
            ->orderby('areaid');
    }

    /**
     * 添加地区
     *
     * @param array $data 地区数据
     * @return int
     */
    public function addArea($data)
    {
        return self::create($data);
    }

    /**
     * 批量添加数据
     *
     * @param array $data 地区数据
     * @return int
     */
    public function batchAddArea($data)
    {
        $clear = array();
        foreach ($data as $_item) {
            $clear[] = array($_item['name'], $_item['parentid'], $_item['joinname']);
        }
        if (!$clear) return false;

        return self::create($clear);
    }

    /**
     * 更新地区数据
     *
     * @param int $areaid 地区ID
     * @param array $data 地区数据
     * @return int
     */
    public function updateArea($areaid, $data)
    {
        return self::where('areaid', $areaid)
            ->update($data);
    }

    /**
     * 根据地区ID删除地区信息
     *
     * @param int $areaid
     * @return boolean
     */
    public function deleteArea($areaid)
    {
        return self::destroy($areaid);
    }

    /**
     * 根据地区ID批量删除地区数据
     *
     * @param array $areaids
     * @return int
     */
    public function batchDeleteArea($areaids)
    {
        return self::destroy($areaids);
    }
}