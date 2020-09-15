<?php
namespace App\Services\forum\ds\dao;

use App\Services\forum\bm\PwForumService;
use App\Services\forum\ds\relation\threadsCateIndex;
use App\Services\forum\ds\traits\threadsCateIndexTrait;

class PwThreadsCateIndexDao extends threadsCateIndex
{
    use threadsCateIndexTrait;

    private $_threadTable = '_bbs_threads';

    public function count($cid)
    {
        return threadsCateIndex::where('cid', $cid)
            ->where('disabled', 0)
            ->count();
    }

    public function countNotInFids($cid, $fids)
    {
        return threadsCateIndex::where('cid', $cid)
            ->where('disabled', 0)
            ->whereNotIn('fid', $fids)
            ->count();
    }

    public function fetch($cid, $order, $perpage)
    {
        return self::where('cid', $cid)
            ->where('disabled', 0)
            ->orderby($order . '_time', 'desc')
            ->paginate($perpage);
    }

    public function fetchNotInFid($cid, $fids, $order, $perpage)
    {
        return self::where('cid', $cid)
            ->where('disabled', 0)
            ->whereNotIn('fid', $fids)
            ->orderby($order, 'desc')
            ->paginate($perpage);
    }

    public function addThread($tid, $fields)
    {
        $fields['tid'] = $tid;
        $fields = $this->_processField($fields);

        return threadsCateIndex::create($fields);
    }

    public function updateThread($tid, $fields, $increaseFields = array())
    {
        $fields = $this->_processField($fields);

        return $this->_update($tid, $fields, $increaseFields);
    }

    public function batchUpdateThread($tids, $fields, $increaseFields = array())
    {
        $fields = $this->_processField($fields);
        return $this->_batchUpdate($tids, $fields, $increaseFields);
    }

    public function revertTopic($tids)
    {
        return DB::update($this->_bindSql('UPDATE %s a LEFT JOIN %s b ON a.tid=b.tid SET a.disabled=b.disabled WHERE a.tid IN (?)', $this->table, $this->_threadTable), [implode(',', $tids)]);
    }

    public function deleteThread($tid)
    {
        return threadsCateIndex::destroy($tid);
    }

    public function batchDeleteThread($tids)
    {
        return threadsCateIndex::destroy($tids);
    }

    public function deleteOver($cid, $limit)
    {
        return threadsCateIndex::where('cid', $cid)
            ->orderby('lastpost_time', 'asc')
            ->chunk($limit, function ($querys) {
                $querys->delete();
                /*foreach($querys as $query){
                    $query->delete();
                }*/
            });
    }

    private function _processField($fields)
    {
        if (isset($fields['fid'])) {
            $fields['cid'] = $fields['fid'] ? app(PwForumService::class)->getCateId($fields['fid']) : 0;
        }
        return $fields;
    }
}