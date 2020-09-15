<?php

namespace App\Services\link\ds\dao;

use App\Services\link\ds\relation\PwLinkType;

class PwLinkTypeDao extends PwLinkType
{
    /**
     * 添加一条分类
     *
     * @param array $data
     * @return int
     */
    public function addLinkType($data)
    {
        return self::create($data);
    }

    /**
     * 删除一条分类
     *
     * @param int $typeId
     * @return bool
     */
    public function _delete($typeId)
    {
        return self::destroy($typeId);
    }

    public function _update($typeId, $data)
    {
        return self::where('typeid', $typeId)
            ->update($data);
    }

    /**
     * 修改多条分类
     *
     * @param array $data
     * @return int
     */
    public function updateLinkTypes($data)
    {
        foreach ($data as $v) {
            $array[] = array($v['typeid'], $v['vieworder'], $v['typename']);

            self::firstOrCreate($array);
        }

        return true;
    }

    /**
     * 根据名称获取
     *
     * @param string $typename
     * @return int
     */
    public function getByName($typename)
    {
        return self::where('typename', $typename)
            ->first();
    }

    /**
     * 获取所有分类
     *
     * @return int
     */
    public function getAllTypes()
    {
        return self::all()
            ->orderby('vieworder', 'asc');
    }

}