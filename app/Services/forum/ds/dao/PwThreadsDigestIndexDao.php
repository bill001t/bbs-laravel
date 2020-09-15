<?php

namespace App\Services\forum\ds\dao;

use App\Services\forum\bm\PwForumService;
use App\Services\forum\ds\relation\threadsDigestIndex;
use App\Services\forum\ds\relation\topicType;
use App\Services\forum\ds\traits\topicTypeTrait;

class PwThreadsDigestIndexDao extends threadsDigestIndex
{
    use topicTypeTrait;

    /**
     * 根据版块分类ID获取精华帖子
     *
     * @param int $cid 类型
     * @param int $limit 查询的条数
     * @param int $offset 开始查询的位置
     * @param string $order 排序方式
     * @return array
     */
    public function getThreadsByCid($cid, $limit, $offset, $order)
    {
        return self::where('cid', $cid)
            ->where('disabled', 0)
            ->orderby($order, 'desc')
            ->paginate($limit);
    }

    /**
     * 根据版块分类ID统计精华帖子
     *
     * @param int $cid
     * @return int
     */
    public function countByCid($cid)
    {
        return topicType::where('cid', $cid)
            ->where('disabled', 0)
            ->count();
    }

    /**
     * 根据版块ID获取该版块的精华列表
     *
     * @param int $fid 版块ID
     * @param int $typeid 主题类型
     * @param int $limit
     * @param int $offset
     * @param string $order
     * @return array
     */
    public function getThreadsByFid($fid, $typeid, $perpage, $order)
    {
        return
            $typeid ?
                self::where('fid', $fid)
                    ->where('disabled', 0)
                    ->where('topic_type', $typeid)
                    ->orderby($order, 'desc')
                    ->paginate($perpage)
                :
                self::where('fid', $fid)
                    ->where('disabled', 0)
                    ->orderby($order, 'desc')
                    ->paginate($perpage);
    }

    /**
     * 根据版块ID统计该版块的精华列表
     *
     * @param int $fid 版块ID
     * @param int $typeid 主题类型
     * @return int
     */
    public function countByFid($fid, $typeid)
    {
        return
            $typeid ?
                topicType::where('fid', $fid)->where('disabled', 0)->get()
                :
                topicType::where('fid', $fid)->where('disabled', 0)->where('topic_type', intval($typeid))->get();
    }

    /**
     * 添加精华
     *
     * @param int $tid
     * @param array $fields
     * @return boolean
     */
    public function addThread($tid, $fields)
    {
        if (1 != $fields['digest']) return true;
        $fields['tid'] = $tid;
        $fields = $this->_processField($fields);
        return threadsDigestIndex::create($fields);
    }

    /**
     * 批量加精
     *
     * @param array $data
     * @return int
     */
    public function batchAddDigest($data)
    {
        $clear = array();
        foreach ($data as $_tmp) {
            $clear[] = array(
                intval($_tmp['tid']),
                intval($_tmp['cid']),
                intval($_tmp['fid']),
                isset($_tmp['disabled']) ? $_tmp['disabled'] : 0,
                intval($_tmp['topic_type']),
                intval($_tmp['created_time']),
                intval($_tmp['lastpost_time']),
                $_tmp['operator'],
                intval($_tmp['operator_userid']),
                intval($_tmp['operator_time'])
            );
        }
        return DB::update('REPLACE INTO ' . $this->table . ' (`tid`, `cid`, `fid`, `disabled`, `topic_type`, `created_time`, `lastpost_time`, `operator`, `operator_userid`, `operator_time`) VALUES ?', [$clear]);
    }

    /**
     * 更新精华相关信息
     *
     * @param int $tid
     * @param array $fields
     * @param array $increaseFields
     * @return int
     */
    public function updateThread($tid, $fields, $increaseFields = array())
    {
        $fields = $this->_processField($fields);
        return threadsDigestIndex::where('tid', $tid)
            ->update($fields);
    }

    /**
     * 批量更新精华相关信息
     *
     * @param array $tids
     * @param array $fields
     * @param array $increaseFields
     * @return boolean
     */
    public function batchUpdateThread($tids, $fields, $increaseFields = array())
    {
        return threadsDigestIndex::whereIn('tid', $tids)
            ->update($fields);
    }

    /**
     * 还原帖子的时候，还原精华设置
     *
     * @param array $tids
     * @return boolean
     */
    public function revertTopic($tids)
    {
        DB::UPDATE('UPDATE ' . $this->table . ' a LEFT JOIN pw_bbs_threads b ON a.tid=b.tid SET a.disabled=b.disabled WHERE a.tid IN %s', [implode(',', $tids)]);
    }

    /**
     * 删除精华相关信息
     *
     * @param int $tid
     * @return int
     */
    public function deleteThread($tid)
    {
        return threadsDigestIndex::destroy($tid);
    }

    /**
     * 批量删除帖子精华相关信息
     *
     * @param array $tids
     * @return boolean
     */
    public function batchDeleteThread($tids)
    {
        return threadsDigestIndex::destroy($tids);
    }

    /**
     * 处理版块对应的分类
     *
     * @param array $fields
     * @return array
     */
    private function _processField($fields)
    {
        if (isset($fields['fid'])) {
            $fields['cid'] = $fields['fid'] ? app(PwForumService::class)->getCateId($fields['fid']) : 0;
        }
        return $fields;
    }
}