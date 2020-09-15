<?php

namespace App\Services\user\bm\register\_do;

use App\Core\ErrorBag;
use App\Services\mobile\bm\PwMobileService;
use App\Services\user\bm\PwRegisterService;
use App\Services\user\bs\PwUserMobile;
use App\Services\user\dm\PwUserInfoDm;
use Core;

/**
 * 注册 - 手机
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 * @package wind
 */
class PwRegisterDoVerifyMobile extends PwRegisterDoBase
{

    /**
     * 构造函数
     *
     * @param PwRegisterService $pwUserRegister
     * @param string $code
     */
    public function __construct(PwRegisterService $pwUserRegister)
    {
        parent::__construct($pwUserRegister);
    }

    /* (non-PHPdoc)
     * @see PwRegisterDoBase::afterRegister()
     */
    public function afterRegister(PwUserInfoDm $userDm)
    {
        if (($result = $this->_check($userDm)) !== true) return false;
        $mobile = $userDm->getField('mobile');
        $this->_getDs()->replaceMobile($userDm->uid, $mobile);
        return true;
    }

    /* (non-PHPdoc)
     * @see PwRegisterDoBase::afterRegister()
     */
    protected function _check(PwUserInfoDm $userDm)
    {
        if (!$userDm->uid) return false;
        $config = Core::C('register');
        if (!$config['active.phone']) return false;
        $mobile = $userDm->getField('mobile');
        $mobileCode = $userDm->getField('mobileCode');
        if (($mobileCheck = app(PwMobileService::class)->checkVerify($mobile, $mobileCode)) instanceof ErrorBag) {
            return false;
        }
        return true;
    }

    /**
     * @return PwUserMobile
     */
    protected function _getDs()
    {
        return app(PwUserMobile::class);
    }
}