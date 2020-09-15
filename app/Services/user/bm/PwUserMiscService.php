<?php

namespace App\Services\user\bm;

use App\Core\ErrorBag;
use App\Core\Hook\SimpleHook;
use App\Core\Tool;
use App\Core\Utility;
use App\Providers\Core;
use App\Services\user\bs\PwUser;
use App\Services\user\bs\PwUserBelong;
use App\Services\user\dm\PwUserInfoDm;
use App\Services\usergroup\bs\PwUserGroups;

class PwUserMiscService
{

    /**
     * 根据版主名单更新数据<1.pw_user_belong 2.pw_user中的groups字段>
     *
     * @param array $manager 所有的版主名单
     */
    public function updateManager($manager)
    {
        $newManager = app(PwUser::class)->fetchUserByName($manager);
        $uids = array_keys(app(PwUserBelong::class)->getUserByGid(5));
        $oldManager = app(PwUser::class)->fetchUserByUid($uids);
        if (!$newManager && !$oldManager) {
            return;
        }
        $newUids = array_keys($newManager);
        $oldUids = array_keys($oldManager);
        $add = array_diff($newUids, $oldUids);
        $del = array_diff($oldUids, $newUids);
        if (!$add && !$del) {
            return;
        }
        app(PwUserInfoDm::class);
        $belongs = $this->getBelongs(array_merge($add, $del));
        foreach ($add as $uid) {
            $dm = new PwUserInfoDm($uid);
            $belong = isset($belongs[$uid]) ? $belongs[$uid] : array();
            if ($newManager[$uid]['groupid']) {
                $belong[5] = 0;
                $dm->setGroupid($newManager[$uid]['groupid']);
            } else {
                $dm->setGroupid(5);
            }
            $dm->setGroups($belong);
            app(PwUser::class)->editUser($dm, PwUser::FETCH_MAIN);
        }
        foreach ($del as $uid) {
            $dm = new PwUserInfoDm($uid);
            $belong = isset($belongs[$uid]) ? $belongs[$uid] : array();
            unset($belong[5]);
            if ($oldManager[$uid]['groupid'] == 5) {
                $dm->setGroupid(0);
            } else {
                $dm->setGroupid($oldManager[$uid]['groupid']);
            }
            $dm->setGroups($belong);
            app(PwUser::class)->editUser($dm, PwUser::FETCH_MAIN);
        }
    }

    /**
     * 获取用户ID列表里的用户附加组信息
     *
     * @param array $uids
     * @return array
     */
    public function getBelongs($uids)
    {
        $result = array();
        $array = app(PwUserBelong::class)->fetchUserByUid($uids);
        foreach ($result as $key => $value) {
            $result[$value['uid']][$value['gid']] = $value['endtime'];
        }
        return $result;
    }

    /**
     * 判断被选为版主的用户是否都是合法用户
     *
     * 这些用户不允许是有禁言用户和未验证用户
     *
     * @param array $mangers
     * @return ErrorBag|true
     */
    public function filterForumManger($mangers)
    {
        $backGids = array(1 => '默认组', 2 => '游客', 6 => '禁言用户', 7 => '未验证会员');
        $managerList = app(PwUser::class)->fetchUserByName($mangers);
        $_tmp = array();
        foreach ($managerList as $uid => $_item) {
            if (array_key_exists($_item['groupid'], $backGids)) {
                $_tmp[$_item['groupid']][] = $_item['username'];
            }
        }
        if (!$_tmp) return true;
        $back = array();
        foreach ($_tmp as $key => $_value) {
            $back[] = $backGids[$key] . ":" . implode(', ', $_value);
        }
        return new ErrorBag('BBS:forum.back.manager', array('{back}' => implode(';', $back)));
    }
}