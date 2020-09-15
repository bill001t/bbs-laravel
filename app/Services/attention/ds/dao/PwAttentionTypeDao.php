<?php

namespace App\Services\attention\ds\dao;

use App\Services\attention\ds\relation\PwAttentionType;

class PwAttentionTypeDao extends PwAttentionType
{


    public function getType($id)
    {
        return self::find($id);
    }

    public function getTypeByUid($uid)
    {
        return self::where('uid', $uid)
            ->get();
    }

    /**
     * 增加一个分类
     *
     * @param array $fields
     * @return bool
     */
    public function addType($fields)
    {
        return self::create($fields);
    }

    /**
     * 修改一个分类
     *
     * @param array $fields
     * @return bool
     */
    public function editType($id, $fields)
    {
        return self::where('id', $id)
            ->update($fields);
    }

    /**
     * 删除一条分类
     *
     * @param int $id
     * @return bool
     */
    public function deleteType($id)
    {
        return self::destroy($id);
    }
}