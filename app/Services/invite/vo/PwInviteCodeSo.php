<?php

namespace App\Services\invite\vo;

use App\Services\user\bs\PwUser;

class PwInviteCodeSo
{
    private $_data = array();

    /**
     * 获取所有查询数据
     *
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * 设置搜索状态
     *
     * @param int $ifused
     * @return PwUserInviteCodeSearch
     */
    public function setIfused($ifused)
    {
        $this->_data['ifused'] = intval($ifused);
        return $this;
    }

    /**
     * 设置过期时间---查询未过期字段
     *
     * @param int $expireTime
     * @return PwUserInviteCodeSearch
     */
    public function setExpireTime($expireTime)
    {
        $this->_data['expire'] = intval($expireTime);
        return $this;
    }

    /**
     * 设置创建用户ID
     *
     * @param int $uid
     * @return PwUserInviteCodeSearch
     */
    public function setCreatedUid($uid)
    {
        $this->_data['created_userid'] = $uid;
        return $this;
    }

    /**
     * 设置被邀请人的用户ID
     *
     * @param 用户名 $uid
     * @return PwUserInviteCodeSearch
     */
    public function setInvitedUid($uid)
    {
        $this->_data['invite_userid'] = intval($uid);
        return $this;
    }

    /**
     * 设置创建用户名字
     *
     * @param string $username
     * @return PwUserInviteCodeSearch
     */
    public function setCreatedUsername($username)
    {
        $userDs = app(PwUser::class);

        $info = $userDs->getUserByName($username);
        if ($info) {
            $this->_data['created_userid'] = $info['uid'];
        }
        return $this;
    }

    /**
     * 设置注册用户的用户名
     *
     * @param string $username
     * @return PwUserInviteCodeSearch
     */
    public function setInvitedUsername($username)
    {
        /* @var $userDs PwUser */
        $userDs = app(PwUser::class);
        $info = $userDs->getUserByName($username);
        if ($info) {
            $this->_data['invited_userid'] = $info['uid'];
        }
        return $this;
    }
}