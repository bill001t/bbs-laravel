<?php

namespace App\Services\attention\bm;

use App\Services\attention\bs\PwFresh;

/**
 * 新鲜事回复
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PwFreshReplyList.php 13121 2012-07-02 03:47:00Z jieyin $
 * @package src.service.user.srv
 */
class PwFreshReplyList
{
    public $fresh;
    public $id;
    public $bhv;
    protected $_bhv_map = array(
        1 => 'App\Services\attention\bm\reply\PwFreshReplyListFromTopic::class',
        2 => 'App\Services\attention\bm\reply\PwFreshReplyListFromPost::class',
        3 => 'App\Services\attention\bm\reply\PwFreshReplyListFromWeibo::class'
    );

    public function __construct($fresh_id)
    {
        if ($fresh = $this->_getDs()->getFresh($fresh_id)) {
            $class = $this->_bhv_map[$fresh['type']];
            $this->bhv = new $class($fresh);
        }

        $this->fresh = $fresh;
    }

    public function getReplies($limit = 10, $offset = 0)
    {
        if (!$this->bhv) return array();

        return $this->bhv->getReplies($limit, $offset);
    }

    public function getData()
    {
        return $this->fresh;
    }

    protected function _getDs()
    {
        return app(PwFresh::class);
    }
}