<?php

namespace App\Services\user\bm\register\_do;

use App\Core\ErrorBag;
use App\Services\invite\bm\PwInviteFriendService;
use App\Services\user\bm\PwRegisterService;
use App\Services\user\dm\PwUserInfoDm;

/**
 * 用户注册-链接邀请注册的服务
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PwRegisterDoInvite.php 7869 2012-04-12 10:46:46Z xiaoxia.xuxx $
 * @package src.service.user.srv.register.do
 */
class PwRegisterDoInviteFriend extends PwRegisterDoBase
{
    private $code = '';
    private $inviteInfo = array();

    /**
     * 构造函数
     *
     * @param PwRegisterService $pwUserRegister
     * @param string $code
     */
    public function __construct(PwRegisterService $pwUserRegister, $code)
    {
        parent::__construct($pwUserRegister);
        $this->code = $code;
    }

    /* (non-PHPdoc)
     * @param PwUserInfoDm $userDm
     * @see PwRegisterDoBase::beforeRegister()
     */
    public function beforeRegister(PwUserInfoDm $userDm)
    {
        if ($this->bp->config['type'] == 2 || !$this->code) return false;
        /* @var $inviteService PwInviteFriendService */
        $inviteService = app(PwInviteFriendService::class);
        if (($r = $inviteService->checkInviteCode($this->code)) instanceof ErrorBag) {
            return $r;
        }
        $this->inviteInfo = $r;
        return true;
    }

    /* (non-PHPdoc)
     * @see PwRegisterDoBase::afterRegister()
     */
    public function afterRegister(PwUserInfoDm $userDm)
    {
        if ($this->bp->config['type'] == 2 || !$userDm->uid) return false;
        /* @var $inviteService PwInviteFriendService */
        $inviteService = app(PwInviteFriendService::class);
        return $inviteService->inviteRegist($this->code, $userDm->uid);
    }
}