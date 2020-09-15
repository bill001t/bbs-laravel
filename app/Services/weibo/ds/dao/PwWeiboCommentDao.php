<?php

namespace App\Services\weibo\ds\dao;

use App\Services\weibo\ds\relation\PwWeiboComment;

class PwWeiboCommentDao extends PwWeiboComment
{
    /*
    public function getFresh($id) {
        $sql = $this->_bindTable('SELECT * FROM %s WHERE id=?');
        $smt = $this->getConnection()->createStatement($sql);
        return $smt->getOne(array($id));
    }

    public function getFreshByIds($ids){
        $sql = $this->_bindSql('SELECT * FROM %s WHERE id IN %s ORDER BY id DESC', $this->getTable(), $this->sqlImplode($ids));
        $smt = $this->getConnection()->query($sql);
        return $smt->fetchAll();
    }

    public function getFreshByType($type, $srcId) {
        $sql = $this->_bindSql('SELECT * FROM %s WHERE type=? AND src_id IN %s', $this->getTable(), $this->sqlImplode($srcId));
        $smt = $this->getConnection()->createStatement($sql);
        return $smt->queryAll(array($type), 'id');
    }

    public function getWeibos($weibo_ids) {
        $sql = $this->_bindSql('SELECT * FROM %s WHERE weibo_id IN %s', $this->getTable(), $this->sqlImplode($weibo_ids));
        $smt = $this->getConnection()->query($sql);
        return $smt->fetchAll('weibo_id');
    }*/

    public function getComment($weibo_id, $limit, $offset, $asc)
    {
        return self::where('weibo_id', $weibo_id)
            ->orderby('created_time', $asc ? 'ASC' : 'DESC')
            ->paginate($limit);
    }

    public function addComment($fields)
    {
        return self::create($fields);
    }

    public function batchDeleteCommentByWeiboId($weiboIds)
    {
        return self::whereIn('weibo_id', $weiboIds)
            ->delete();
    }

    /*
    public function batchDelete($ids) {
        $sql = $this->_bindSql('DELETE FROM %s WHERE id IN %s', $this->getTable(), $this->sqlImplode($ids));
        $this->getConnection()->execute($sql);
        return true;
    }

    public function updateForum($fid, $fields, $increaseFields = array()) {
        if (!$fields = $this->_filterStruct($fields)) {
            return false;
        }
        $sql = $this->_bindTable('UPDATE %s SET ') . $this->sqlSingle($fields) . ' WHERE fid=?';
        $smt = $this->getConnection()->createStatement($sql);
        return $smt->update(array($fid));
    }*/
}