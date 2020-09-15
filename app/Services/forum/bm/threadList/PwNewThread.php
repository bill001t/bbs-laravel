<?php
namespace App\Services\forum\bm\threadList;

use App\Services\forum\bs\PwSpecialSort;
use App\Services\forum\bs\PwThread;
use App\Services\forum\bs\PwThreadIndex;

/*Wind::import('SRV:forum.srv.threadList.PwThreadDataSource');*/

/**
 * 帖子列表数据接口 / 普通列表
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PwNewThread.php 17054 2012-08-30 10:51:39Z jieyin $
 * @package forum
 */
class PwNewThread extends PwThreadDataSource
{

    protected $forbidFids;
    protected $order;
    protected $specialSortTids;
    protected $count;

    public function __construct($forbidFids = array())
    {
        $this->forbidFids = $forbidFids;
        $this->specialSortTids = $this->_getSpecialSortDs()->getSpecialSortByTypeExtra('topped', 3)->pluck('tid')->all();
        $this->count = count($this->specialSortTids);
    }

    public function setOrderBy($order)
    {
        $this->order = $order;
        if ($order != 'lastpost') {
            $this->urlArgs['order'] = $order;
        }
    }

    public function getTotal()
    {
        return $this->_getThreadIndexDs()->countThreadNotInFids($this->forbidFids) + $this->count;
    }

    public function getData($perpage = 15, $offset = '')
    {
        /*$threaddb = array();
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
            $tids = $this->_getThreadIndexDs()->fetchNotInFid($this->forbidFids, $limit, $offset, $this->order);
            $tmp = $this->_getThreadDs()->fetchThread($tids);
            $tmp = $this->_sort($tmp, $tids);
            $tmp && $threaddb = array_merge($threaddb,$tmp);
        }
        return $threaddb;*/
        $threaddb = array();

        $array = $this->_getThreadDs()->fetchThreadByTid($this->specialSortTids, $perpage);
        foreach ($array as $key => $value) {
            $value['issort'] = true;
            $threaddb[] = $value;
        }

        $tids = $this->_getThreadIndexDs()->fetchNotInFidsAndTids($this->forbidFids, $this->specialSortTids, $perpage, $this->order)->keys()->all();
        $tmp = $this->_getThreadDs()->fetchThread($tids);
        //$tmp = $this->_sort($tmp, $tids);

		return array_merge($threaddb, $tmp->all());
	}

    protected function _sort($data, $sort)
    {
        $result = array();
        foreach ($sort as $tid) {
            $result[$tid] = $data[$tid];
        }
        return $result;
    }

    protected function _getThreadDs()
    {
        return app(PwThread::class);
    }

    protected function _getThreadIndexDs()
    {
        return app(PwThreadIndex::class);
    }

    protected function _getSpecialSortDs()
    {
        return app(PwSpecialSort::class);
    }
}