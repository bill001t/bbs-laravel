<?php

namespace App\Services\link\ds\dao;

use App\Services\link\ds\relation\PwLink;
use DB;

class PwLinkSearchDao extends PwLink
{
    public function countSearchLink($field)
    {
        $sql = self::whereRaw('1 = 1');

        $sql = $this->_buildCondition($sql, $field);

        return $sql->get();
    }

    public function searchLink($field, $orderby, $limit, $offset)
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
                case 'lid':
                    $sql = $sql->where('lid', $value);
                    break;
                case 'name':
                    $sql = $sql->where('name', $value);
                    break;
                case 'iflogo':
                    $sql = $sql->where('iflogo', $value);
                    break;
                case 'ifcheck':
                    $sql = $sql->where('ifcheck', $value);
                    break;
                case 'typeid':
                    $sql = $sql->whereHas(PwLinkType::class, function ($query) use ($value) {
                        $query->where('ifcheck', $value);
                    });
            }
        }
        return $sql;
    }

    protected function _buildOrderby($sql, $orderby)
    {
        foreach ($orderby as $key => $value) {
            switch ($key) {
                case 'vieworder':
                    $sql->orderby('vieworder', ($value ? 'ASC' : 'DESC'));
                    break;
            }
        }
        return $sql;
    }
}