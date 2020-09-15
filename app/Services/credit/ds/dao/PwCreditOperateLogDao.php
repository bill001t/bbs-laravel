<?php

namespace App\Services\credit\ds\dao;

use App\Core\BaseTrait;
use App\Services\credit\ds\relation\creditLogOperate;

/**
 * 积分操作次数统计DAO
 */
class PwCreditOperateLogDao extends creditLogOperate
{
    use BaseTrait;

    public function get($uid)
    {
        return self::where('uid', $uid)
            ->get();
    }

    public function batchAdd($data)
    {
        return self::_batchReplace_($data);
    }
}