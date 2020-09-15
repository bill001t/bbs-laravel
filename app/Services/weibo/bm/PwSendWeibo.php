<?php

namespace App\Services\weibo\bm;

use App\Core\ErrorBag;
use App\Core\Tool;
use App\Services\attention\bs\PwFresh;
use App\Services\weibo\bs\PwWeibo;
use App\Services\weibo\dm\PwWeiboDm;

/**
 * 微博发布服务
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PwSendWeibo.php 5519 2012-03-06 07:13:36Z jieyin $
 * @package fresh
 */
class PwSendWeibo
{

    public $user;

    public function __construct(PwUserBo $user)
    {
        $this->user = $user;
    }

    public function check()
    {
        return true;
    }

    /**
     * 发布一条微博
     *
     * @param object $dm PwWeiboDm
     * @return bool|ErrorBag
     */
    public function send(PwWeiboDm $dm)
    {
        if (($result = $this->check()) instanceof ErrorBag) {
            return $result;
        }
        $dm->setCreatedUser($this->user->uid, $this->user->username);
        $dm->setCreatedTime(Tool::getTime());

        $weibo_id = $this->_getDs()->addWeibo($dm);
        $this->_getFresh()->send($this->user->uid, PwFresh::TYPE_WEIBO, $weibo_id);

        return $weibo_id;
    }

    protected function _getFresh()
    {
        return app(PwFresh::class);
    }

    protected function _getDs()
    {
        return app(PwWeibo::class);
    }
}