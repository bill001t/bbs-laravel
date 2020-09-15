<?php

namespace App\Services\user\bm\login\injector;

use App\Core\Hook\BaseHookInjector;
use App\Services\user\bm\login\_do\PwLoginDoInviteFriend;
use Core;
use Request;

/**
 * 用户登录-链接邀请
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PwRegisterDoInviteInjector.php 6177 2012-03-19 03:50:39Z xiaoxia.xuxx $
 * @package src.service.user.srv.register.ingector
 */
class PwLoginDoInviteFriendInjector extends BaseHookInjector
{

    /**
     * 链接邀请的注入插件
     *
     * @return PwLoginDoInviteFriend
     */
    public function run()
    {
        $loginInvite = null;
        $invite = Request::get('invite');
        if (2 != Core::C('register', 'type') && $invite) {
            $loginInvite = new PwLoginDoInviteFriend($this->bp, $invite);
        }
        return $loginInvite;
    }
}