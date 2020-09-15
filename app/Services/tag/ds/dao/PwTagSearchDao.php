<?php

namespace App\Services\tag\ds\dao;

use App\Services\tag\ds\relation\PwTagSearch;

class PwTagSearchDao extends PwTagSearch
{
    public function countSearchTag($field)
    {
        $sql = self::whereRaw('1 = 1');

        $sql = $this->_buildCondition($sql, $field);

        return $sql->count();
    }

    public function searchTag($field, $orderby, $limit, $offset)
    {
        $sql = self::whereRaw('1 = 1');

        $sql = $this->_buildCondition($sql, $field);

        $sql = $this->_buildOrderby($sql, $orderby);

        return $sql->paginate($limit);
    }

    protected function _buildCondition($sql, $field)
    {
        foreach ($field as $key => $value) {
            switch ($key) {
                case 'tag_id':
                    $sql = $sql->where('tag_id', $value);
                    break;
                case 'category_id':
                    $sql = $sql->whereHas(PwTagCategory::class, function($query) use($value){
                        $query->where('category_id', $value);
                    });
                    break;
                case 'iflogo':
                    $sql = $sql->where('iflogo', $value);
                    break;
                case 'ifhot':
                    $sql = $sql->where('ifhot', $value);
                    break;
            }
        }
        return $sql;
    }

    protected function _buildOrderby($sql, $orderby)
    {
        foreach ($orderby as $key => $value) {
            switch ($key) {
                case 'attention_count':
                    $sql = $sql->orderby('attention_count', ($value ? 'ASC' : 'DESC'));
                    break;
                case 'content_count':
                    $sql = $sql->orderby('content_count', ($value ? 'ASC' : 'DESC'));
                    break;
                case 'created_time':
                    $sql = $sql->orderby('created_time', ($value ? 'ASC' : 'DESC'));
                    break;
            }
        }
        return $sql;
    }
}