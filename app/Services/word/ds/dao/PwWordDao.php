<?php

namespace App\Services\word\ds\dao;

use App\Services\word\ds\relation\PwWord;
use DB;

class PwWordDao extends PwWord
{
    public function get($wordId)
    {
        return self::find($wordId);
    }

    public function getByWord($word)
    {
        return self::where('word', $word)
            ->first();
    }

    public function getByType($type)
    {
        return self::where('word_type', $type)
            ->get();
    }

    public function fetch($wordIds)
    {
        return self::whereIn('word_id', $wordIds)
            ->get();
    }

    public function fetchByWord($word)
    {
        return self::where('word', $word)
            ->get();
    }

    public function getWordList($limit, $offset)
    {
        return self::all()
            ->orderby('created_time', 'desc')
            ->paginate($limit);
    }

    public function count()
    {
        return self::count();
    }

    public function countByFrom($from)
    {
        return self::where('word_from', $from)
            ->count();
    }

    public function add($fieldData)
    {
        return self::create($fieldData);
    }

    public function _delete($wordId)
    {
        return self::destroy($wordId);
    }

    public function deleteByType($type)
    {
        return self::where('word_type', $type)
            ->delete();
    }

    public function deleteByKeyword($keyword)
    {
        return self::where('word', 'like', "%$keyword%")
            ->delete();
    }

    public function deleteByTypeAndKeyword($type, $keyword)
    {
        return self::where('word', 'like', "%$keyword%")
            ->where('word_type', $type)
            ->delete();
    }

    public function _update($wordId, $fieldData)
    {
        return self::where('word_id', $wordId)
            ->update($fieldData);
    }

    public function batchUpdate($wordIds, $fieldData)
    {
        return self::whereIn('word_id', $wordIds)
            ->update($fieldData);
    }

    public function batchDelete($wordIds)
    {
        return self::destroy($wordIds);
    }

    public function countSearchWord($condition)
    {
        $sql = self::whereRaw('1 = 1');

        $sql = $this->_buildCondition($sql, $condition);

        return $sql->count();
    }

    public function searchWord($condition, $limit, $offset)
    {
        $sql = self::whereRaw('1 = 1');

        $sql = $this->_buildCondition($sql, $condition);

        return $sql->orderby('created_time', 'desc')
            ->paginate($limit);
    }

    private function _buildCondition($sql, $condition)
    {
        if (!$condition) return $sql;

        foreach ($condition as $key => $value) {
            switch ($key) {
                case 'word_type':
                    $sql = $sql->where('word_type', $value);
                    break;
                case 'word':
                    $sql = $sql->where('word_type', 'like', "%$value%");
                    break;
            }
        }

        return $sql;
    }

    /**
     * 获得所有敏感词(需谨慎)
     *
     */
    public function fetchAllWord()
    {
        return self::all()
            ->orderby('word_id', 'desc');
    }

    /**
     * 清空数据(需谨慎)
     *
     */
    public function truncate()
    {
        return DB::statement('TRUNCATE TABLE ' . $this->table);
    }

    /**
     * 更新所有类型(需谨慎，仅后台使用)
     *
     */
    public function updateAll($fieldData)
    {
        return self::update($fieldData);
    }
}