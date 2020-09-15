<?php

namespace App\Services\weibo\bm;

use App\Core\ErrorBag;
use App\Core\Hook\SimpleHook;
use App\Services\weibo\bs\PwWeibo;
use App\Services\weibo\dm\PwWeiboDm;

/**
 * 微博公共服务
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PwWeiboService.php 17151 2012-08-31 08:21:36Z jieyin $
 * @package fresh
 */
class PwWeiboService
{

    /**
     * 添加一条微博评论
     *
     * @param object $dm PwWeiboCommnetDm
     * @return bool|ErrorBag
     */
    public function addComment(PwWeiboCommnetDm $dm, PwUserBo $user)
    {
        if (($result = $this->_getDs()->addComment($dm)) instanceof ErrorBag) {
            return $result;
        }
        $weibo_id = $dm->getField('weibo_id');
        $dm1 = new PwWeiboDm($weibo_id);
        $dm1->addComments(1);
        $this->_getDs()->updateWeibo($dm1);

        SimpleHook::getInstance('weibo_addComment')->runDo($result, $dm, $user);

        return $result;
    }

    protected function _getDs()
    {
        return app(PwWeibo::class);
    }
}