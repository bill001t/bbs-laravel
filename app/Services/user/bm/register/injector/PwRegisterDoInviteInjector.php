<?php

namespace App\Services\user\bm\register\injector;

use App\Core\Hook\BaseHookInjector;
use App\Services\user\bm\register\_do\PwRegisterDoInvite;
use Request;

/**
 * 用户注册-邀请注册方式
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PwRegisterDoInviteInjector.php 15637 2012-08-09 09:20:55Z xiaoxia.xuxx $
 * @package src.service.user.srv.register.ingector
 */
class PwRegisterDoInviteInjector extends BaseHookInjector
{

    /**
     * 用户注册的注入插件
     *
     * @return PwRegisterDoInvite
     */
    public function run()
    {
        $registerInvite = null;
        if ($this->bp->isOpenInvite) {
            $registerInvite = new PwRegisterDoInvite($this->bp, Request::get('invitecode'));
        }
        return $registerInvite;
    }
}