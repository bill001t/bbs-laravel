<?php
namespace App\Services\forum\bm\threadList;

use App\Services\forum\bm\PwForumService;
use App\Services\forum\bs\PwForum;
use App\Services\forum\bs\PwForumUser;
use App\Services\forum\bs\PwSpecialSort;
use App\Services\forum\bs\PwThread;
use App\Services\forum\bs\PwThreadIndex;
use App\Services\user\bo\PwUserBo;

/**
 * 帖子列表数据接口 / 普通列表
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: PwMyForumThread.php 19290 2012-10-12 08:13:34Z xiaoxia.xuxx $
 * @package forum
 */
class PwMyForumThread extends PwThreadDataSource
{
    protected $fids;
    protected $order;
    protected $specialSortTids;
    protected $count;

    public function __construct(PwUserBo $user)
    {
        $fids = array_keys(app(PwForumUser::class)->getFroumByUid($user->uid));
        $this->fids = app(PwForumService::class)->getAllowVisitForum($user, app(PwForum::class)->fetchForum($fids));
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
        return $this->_getThreadIndexDs()->countThreadInFids($this->fids) + $this->count;
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
            $tids = $this->_getThreadIndexDs()->fetchInFid($this->fids, $limit, $offset, $this->order);
            $tmp = $this->_getThreadDs()->fetchThread($tids);
            $tmp = $this->_sort($tmp, $tids);
            $tmp && $threaddb = array_merge($threaddb, $tmp);
        }
        return $threaddb;
    }

    protected function _getThreadDs()
    {
        return app(PwThread::class);
    }

    protected function _getThreadIndexDs()
    {
        return app(PwThreadIndex::class);
    }

    protected function _sort($data, $sort)
    {
        $result = array();
        foreach ($sort as $tid) {
            $result[$tid] = $data[$tid];
        }
        return $result;
    }

    protected function _getSpecialSortDs()
    {
        return app(PwSpecialSort::class);
    }
}