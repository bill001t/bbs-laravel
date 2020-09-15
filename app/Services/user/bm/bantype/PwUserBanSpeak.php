<?php

namespace App\Services\user\bm\bantype;

use App\Core\ErrorBag;
use App\Services\forum\bm\PwForumMiscService;
use App\Services\forum\bs\PwForum;
use App\Services\user\bs\PwUser;
use App\Services\user\dm\PwUserBanInfoDm;
use App\Services\user\dm\PwUserInfoDm;
use App\Services\usergroup\bm\PwUserGroupsService;
use Core;

/**
 * 用户禁止-发言类型
 *
 * @author xiaoxia.xu <x_824@sina.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: PwUserBanSpeak.php 22749 2012-12-27 03:14:34Z xiaoxia.xuxx $
 * @package src.service.user.srv.bantype
 */
class PwUserBanSpeak implements PwUserBanTypeInterface
{

    /* (non-PHPdoc)
     * @see PwUserBanTypeInterface::afterBan()
     */
    public function afterBan(PwUserBanInfoDm $dm)
    {
        //【禁止用户】禁止发言用户组
        $userDm = new PwUserInfoDm($dm->getField('uid'));
        $userDm->setGroupid(6)
            ->setGroups(array());//用户禁止，设置用户的组为禁止发言组，删除用户拥有的其他附加组
        $result = $this->_getUserDs()->editUser($userDm, PwUser::FETCH_MAIN);
        if (!$result instanceof ErrorBag) {
            $userinfo = $this->_getUserDs()->getUserByUid($dm->getField('uid'), PwUser::FETCH_MAIN);
            app(PwForumMiscService::class)->updateDataByUser($userinfo['username']);
        }
        return 6;
    }

    /* (non-PHPdoc)
     * @see PwUserBanTypeInterface::deleteBan()
     */
    public function deleteBan($uid)
    {
        if (!$uid) return false;
        $userDm = new PwUserInfoDm($uid);
        $userDm->setGroupid(0)
            ->setGroups(array());
        /* @var $groupService PwUserGroupsService */
        $groupService = app(PwUserGroupsService::class);
        $strategy = Core::C('site', 'upgradestrategy');
        $_credit = $this->_getUserDs()->getUserByUid($uid, PwUser::FETCH_DATA);
        $credit = $groupService->calculateCredit($strategy, $_credit);
        $memberid = $groupService->calculateLevel($credit, 'member');
        $userDm->setMemberid($memberid);
        /* @var $userDs PwUser */
        $userDs = app(PwUser::class);
        $userDs->editUser($userDm, PwUser::FETCH_MAIN);
        return $memberid;
    }

    /* (non-PHPdoc)
     * @see PwUserBanTypeInterface::getExtension()
     */
    public function getExtension($fid)
    {
        if (0 >= $fid) return '全局';
        /* @var $forumDs PwForum */
        $forumDs = app(PwForum::class);
        $info = $forumDs->getForum($fid);
        return $info['name'];
    }

    /**
     * 获得USERDs
     *
     * @return PwUser
     */
    private function _getUserDs()
    {
        return app(PwUser::class);
    }
}