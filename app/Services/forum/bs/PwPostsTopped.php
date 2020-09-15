<?php

namespace App\Services\forum\bs;

use App\Core\ErrorBag;
use App\Services\forum\dm\PwPostsToppedDm;
use App\Services\forum\ds\dao\PwPostsToppedDao;

/**
 * 帖内置顶
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 * @package wind
 */
class PwPostsTopped
{
    private static $_dao;

    /**
     * 获取某个帖子的所有置顶楼层
     *
     * @param int $tid
     * @return array
     */
    public function getByTid($tid, $limit = 20, $offset = 0)
    {
        $tid = intval($tid);
        if ($tid < 1) return false;
        return $this->_getDao()->getByTid($tid, $limit, $offset);
    }

    /**
     * 增加一个置顶楼层
     *
     * @param PwPostsToppedDm $dm
     * @return bool
     */
    public function addTopped(PwPostsToppedDm $dm)
    {
        if (($result = $dm->beforeAdd()) instanceof ErrorBag) return $result;
        return $this->_getDao()->add($dm->getData());
    }

    /**
     * 删除某个置顶楼层
     *
     * @param int $pid
     * @return bool
     */
    public function deleteTopped($pid)
    {
        $pid = intval($pid);
        if ($pid < 1) return false;
        return $this->_getDao()->_delete($pid);
    }

    /**
     * 删除某个置顶楼层
     *
     * @param int $pid
     * @return bool
     */
    public function batchDeleteTopped($pids)
    {
        if (!is_array($pids) || !$pids) return false;
        return $this->_getDao()->batchDelete($pids);
    }

    /**
     * 删除某个置顶楼层
     *
     * @param int $pid
     * @return bool
     */
    public function updateTopped($pid, PwPostsToppedDm $dm)
    {
        $pid = intval($pid);
        if ($pid < 1) return false;
        if (($result = $dm->beforeUpdate()) instanceof ErrorBag) return $result;
        return $this->_getDao()->_update($pid, $dm->getData());
    }

    /**
     * PwPostsToppedDao
     *
     * @return PwPostsToppedDao
     */
    protected function _getDao()
    {
        if (is_null(self::$_dao)) {
            return self::$_dao = new PwPostsToppedDao();
        }
        return self::$_dao;
    }
}