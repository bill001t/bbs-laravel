<?php

namespace App\Services\user\avatar;


/**
 * 用户头像公共服务
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @license http://www.phpwind.com
 * @version $Id: WindidAvatarApi.php 32085 2014-08-20 08:48:50Z gao.wanggao $
 * @package windid.service.avatar
 */
class Avatar
{
    /**
     * 获取用户头像
     * @param $uid
     * @param $size big middle small
     * @return string
     */
    public function getAvatar($uid, $size = 'middle')
    {
        return $this->_getService()->getAvatar($uid, $size);
    }

    protected function _getService()
    {
        return app(WindidUserService::class);
    }
}