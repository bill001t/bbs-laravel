<?php
namespace App\Services\forum\bm\threadList;
use App\Services\forum\bs\PwThread;
use App\Services\forum\bs\PwThreadDigestIndex;

/**
 * 帖子-版块分类下-精华帖子列表
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: PwCateDigestThread.php 21191 2012-11-30 06:22:06Z jieyin $
 * @package src.service.forum.srv.threadList
 */
class PwCateDigestThread extends PwThreadDataSource
{

    private $fid;
    private $orderby;

    /**
     * 构造方法
     *
     * @param int $fid
     * @param string $orderby
     */
    public function __construct($fid, $orderby)
    {
        $this->fid = $fid;
        $this->orderby = $orderby == 'postdate_time' ? 'postdate_time' : 'lastpost_time';
        $this->urlArgs['tab'] = 'digest';
    }

    /* (non-PHPdoc)
     * @see PwThreadDataSource::getTotal()
     */
    public function getTotal()
    {
        return $this->_getThreadDigestIndexDs()->countByCid($this->fid);
    }

    /* (non-PHPdoc)
     * @see PwThreadDataSource::getData()
     */
    public function getData($limit, $offset = '')
    {
        $threads = $this->_getThreadDigestIndexDs()->getThreadsByCid($this->fid, $limit, $offset, $this->orderby);
        $tids = $threads->pluck('tid')->all();
        $threaddb = $this->_getThreadDs()->fetchThread($tids);
        return $this->_sort($threaddb, $tids);
    }

    /**
     * 帖子DS
     *
     * @return PwThread
     */
    protected function _getThreadDs()
    {
        return app(PwThread::class);
    }

    /**
     * 精华DS
     *
     * @return PwThreadDigestIndex
     */
    protected function _getThreadDigestIndexDs()
    {
        return app(PwThreadDigestIndex::class);
    }

    /**
     * 根据帖子ID$sort排序
     *
     * @param array $data
     * @param array $sort
     * @return array
     */
    protected function _sort($data, $sort)
    {
        $result = array();
        foreach ($sort as $tid) {
            $result[$tid] = $data[$tid];
        }
        return $result;
    }
}