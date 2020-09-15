<?php

namespace App\Services\invite\dm;

use App\Core\BaseDm;
use App\Core\ErrorBag;

class PwInviteCodeDm extends BaseDm
{
    public $code = '';

    /**
     * 设置邀请码
     *
     * @param string $code
     * @return PwInviteCodeDm
     */
    public function setCode($code)
    {
        $this->_data['code'] = $code;
        return $this;
    }

    /**
     * 设置邀请码购买者ID
     *
     * @param int $uid
     * @return PwInviteCodeDm
     */
    public function setCreateUid($uid)
    {
        $this->_data['created_userid'] = $uid;
        return $this;
    }

    /**
     * 设置被邀请的用户ID
     *
     * @param int $uid
     * @return PwInviteCodeDm
     */
    public function setInvitedUid($uid)
    {
        $this->_data['invited_userid'] = $uid;
        return $this;
    }

    /**
     * 设置邀请码的状态
     *
     * @param int $ifused
     * @return PwInviteCodeDm
     */
    public function setIfused($ifused)
    {
        $this->_data['ifused'] = $ifused;
        return $this;
    }

    /**
     * 设置邀请码的购买时间
     *
     * @param int $createdTime
     * @return PwInviteCodeDm
     */
    public function setCreatedTime($createdTime)
    {
        $this->_data['created_time'] = $createdTime;
        return $this;
    }

    /**
     * 设置邀请码的被使用时间
     *
     * @param int $modifiedTime
     * @return PwInviteCodeDm
     */
    public function setModifiedTime($modifiedTime)
    {
        $this->_data['modified_time'] = $modifiedTime;
        return $this;
    }

    /* (non-PHPdoc)
     * @see BaseDm::_beforeAdd()
     */
    protected function _beforeAdd()
    {
        if (!$this->_data['code']) return new ErrorBag('USER:invite.code.require');
        $this->_data['ifused'] = 0;
        return true;
    }

    /* (non-PHPdoc)
     * @see BaseDm::_beforeUpdate()
     */
    protected function _beforeUpdate()
    {
        if (!$this->_data['code']) return new ErrorBag('USER:invite.code.illage');
        $this->code = $this->_data['code'];
        unset($this->_data['code']);
        return true;
    }
}