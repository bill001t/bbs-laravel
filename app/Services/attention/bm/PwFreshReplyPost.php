<?php

namespace App\Services\attention\bm;

use App\Services\attention\bs\PwFresh;

/**
 * 新鲜事回复
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PwFreshReplyPost.php 15354 2012-08-03 09:29:10Z jieyin $
 * @package src.service.user.srv
 */
class PwFreshReplyPost
{

    public $bhv;
    public $fresh;

    protected $_bhv_map = array(
        1 => 'App\Services\attention\bm\reply\PwFreshReplyByPost::class',
        2 => 'App\Services\attention\bm\reply\PwFreshReplyByPost::class',
        3 => 'App\Services\attention\bm\reply\PwFreshReplyByWeibo::class'
    );

    public function __construct($fresh_id, PwUserBo $user)
    {
        $fresh = $this->_getDs()->getFresh($fresh_id);
        $class = $this->_bhv_map[$fresh['type']];
        $this->bhv = new $class($fresh, $user);
        $this->fresh = $fresh;
    }

    public function check()
    {
        return $this->bhv->check();
    }

    public function setContent($content)
    {
        $this->bhv->setContent($content);
        return $this;
    }

    public function setIsTransmit($isTransmit)
    {
        $this->bhv->setIsTransmit($isTransmit);
        return $this;
    }

    public function execute()
    {
        return $this->bhv->execute();
    }

    public function getData()
    {
        return $this->fresh;
    }

    public function getIscheck()
    {
        return $this->bhv->getIscheck();
    }

    public function getIsuseubb()
    {
        return $this->bhv->getIsuseubb();
    }

    public function getRemindUser()
    {
        return $this->bhv->getRemindUser();
    }

    public function getNewFreshSrcId()
    {
        return $this->bhv->getNewFreshSrcId();
    }

    protected function _getDs()
    {
        return app(PwFresh::class);
    }
}