<?php
namespace App\Services\forum\bm\threadList;

use App\Services\forum\bs\PwThreadCateIndex;
use App\Services\forum\bs\PwSpecialSort;
use App\Services\forum\bs\PwThread;

/**
 * 帖子列表数据接口 / 普通列表
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PwCateThread.php 24749 2013-02-20 03:21:00Z jieyin $
 * @package forum
 */
class PwCateThread extends PwThreadDataSource
{

    protected $fid;
    protected $forum;
    protected $forbidFids;
    protected $orderby = 'lastpost';

    protected $specialSortTids;
    protected $count;

    public function __construct($forum, $forbidFids = array())
    {
        $this->forum = $forum;
        $this->fid = $forum->fid;
        $this->forbidFids = $forbidFids;
//dd($this->_getSpecialSortDs()->getSpecialSortByFid($forum->fid));
        $this->specialSortTids = array_keys($this->_getSpecialSortDs()->getSpecialSortByFid($forum->fid)->all());
        $this->count = count($this->specialSortTids);
    }

    protected function _getSpecialSortDs()
    {
        return app(PwSpecialSort::class);
    }

    public function setOrderby($order)
    {
        if ($order == 'postdate') {
            $this->orderby = $order;
        }
    }

    public function getTotal()
    {
        return $this->_getThreadCateIndexDs()->countNotInFids($this->fid, $this->forbidFids);
    }

    protected function _getThreadCateIndexDs()
    {
        return app(PwThreadCateIndex::class);
    }

    public function getData($limit, $offset = '')
    {
        $threaddb = array();
        if ($offset < $this->count) {
            $array = $this->_getThreadDs()->fetchThreadByTid($this->specialSortTids, $limit, $offset);
            foreach ($array as $key => $value) {
                $value['issort'] = true;
                $threaddb[] = $value;
            }
            $limit -= count($threaddb);
        }
        $offset -= min($this->count, $offset);
        if ($limit > 0) {
            $tids = $this->_getThreadCateIndexDs()->fetchNotInFid($this->fid, $this->forbidFids, $limit, $offset, $this->orderby);
            $array = $this->_getThreadDs()->fetchThread($tids);
//            $array = $this->_sort($array, $tids);
            foreach ($array as $key => $value) {
                $threaddb[] = $value;
            }
        }
        return $threaddb;
    }

    protected function _getThreadDs()
    {
        return app(PwThread::class);
    }

    protected function _sort($data, $sort)
    {
        $result = array();
        foreach ($sort as $tid) {
            $result[$tid] = $data[$tid];
        }
        return $result;
    }
}