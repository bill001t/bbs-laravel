<?php

namespace App\Services\remind\ds\dao;

use App\Services\remind\ds\relation\PwRemind;

class PwRemindDao extends PwRemind
{
    /**
     * 查询一条
     *
     * @param int $uid
     * @return bool
     */
    public function get($uid)
    {
        return self::find($uid);
    }

    /**
     * 添加
     *
     * @param array $data
     * @return bool
     */
    public function add($data)
    {
        return self::create($data);
    }

    /**
     * 修改
     *
     * @param array $data
     * @return bool
     */
    public function replace($data)
    {
        return self::firstOrCreate($data);
    }

    /**
     * 删除
     *
     * @param int $uid
     * @return bool
     */
    public function deleteByUid($uid)
    {
        return self::destroy($uid);
    }

}