<?php

namespace App\Services\user\bm\register\_do;

use App\Services\site\bs\PwBbsinfo;
use App\Services\site\dm\PwBbsinfoDm;
use App\Services\user\dm\PwUserInfoDm;

/**
 * 用户注册-更新站点统计信息
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PwRegisterDoUpdateBbsInfo.php 24134 2013-01-22 06:19:24Z xiaoxia.xuxx $
 * @package src.service.user.srv.register.do
 */
class PwRegisterDoUpdateBbsInfo extends PwRegisterDoBase
{
    private $code = '';
    private $inviteInfo = array();

    /* (non-PHPdoc)
     * @param PwUserInfoDm $userDm
     * @see PwRegisterDoBase::beforeRegister()
     */
    public function beforeRegister(PwUserInfoDm $userDm)
    {
        return true;
    }

    /* (non-PHPdoc)
     * @see PwRegisterDoBase::afterRegister()
     */
    public function afterRegister(PwUserInfoDm $userDm)
    {
        $dm = new PwBbsinfoDm();
        $dm->setNewmember($userDm->getField('username'))->addTotalmember(1);
        app(PwBbsinfo::class)->updateInfo($dm);
        return true;
    }
}