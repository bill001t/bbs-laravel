<?php

namespace App\Services\user\bm\logout\_do;

use App\Services\user\bm\logout\PwLogoutDoBase;
use App\Services\user\bo\PwUserBo;
use App\Services\user\bs\PwUser;
use App\Services\user\dm\PwUserInfoDm;
use Core;

/**
 * 为了用户可以及时的更新在线状态，用户退出之前更新用户的最后访问时间
 * 用户退出  更新用户最后的访问时间
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: PwLogoutDoUpdateLastvisit.php 18618 2012-09-24 09:31:00Z jieyin $
 * @package src.service.user.srv.logout.do
 */
class PwLogoutDoUpdateLastvisit extends PwLogoutDoBase
{

    /* (non-PHPdoc)
     * @see PwLogoutDoBase::beforeLogout()
     */
    public function beforeLogout(PwUserBo $bo)
    {
        if (!$bo->isExists()) return true;
        $onlineTime = intval(Core::C('site', 'onlinetime'));
        if ($onlineTime <= 0) return true;
        $newLastVisit = $bo->info['lastvisit'] - ($onlineTime * 60);
        $dm = new PwUserInfoDm($bo->uid);
        $dm->setLastvisit($newLastVisit);
        /* @var $userDs PwUser */
        $userDs = app(PwUser::class);
        $userDs->editUser($dm, PwUser::FETCH_DATA);
        return true;
    }
}