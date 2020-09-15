<?php

namespace App\Services\user\bm\logout;

use App\Services\user\bo\PwUserBo;

abstract class PwLogoutDoBase
{
    /**
     * 用户退出之前的更新
     *
     * @param PwUserBo $bo
     */
    abstract public function beforeLogout(PwUserBo $bo);
}