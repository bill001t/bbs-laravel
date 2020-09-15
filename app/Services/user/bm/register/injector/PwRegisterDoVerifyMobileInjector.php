<?php

namespace App\Services\user\bm\register\injector;

use App\Core\Hook\BaseHookInjector;
use Request;
use App\Services\user\bm\register\_do\PwRegisterDoVerifyMobile;

/**
 * 用户注册-链接邀请
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PwRegisterDoInviteInjector.php 6177 2012-03-19 03:50:39Z xiaoxia.xuxx $
 * @package src.service.user.srv.register.ingector
 */
class PwRegisterDoVerifyMobileInjector extends BaseHookInjector
{

    /**
     * 链接邀请的注入插件
     *
     * @return PwRegisterDoInviteFriend
     */
    public function run()
    {
        $register = null;
        if ($this->bp->isOpenMobileCheck && Request::get('mobile') && Request::get('mobileCode')) {
            $register = new PwRegisterDoVerifyMobile($this->bp);
        }
        return $register;
    }
}