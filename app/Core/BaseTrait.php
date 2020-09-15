<?php

namespace App\Core;

use App\Core\Hook\SimpleHook;
use DB;

Trait BaseTrait
{
    protected function _update_($id, $fields = array(), $increaseFields = array())
    {
        foreach ($increaseFields as $k => $v) {
            self::where($this->id, $id)
                ->increment($k, $v);
        }

        return self::where($this->id, $id)
            ->update($fields);
    }

    protected function _update($id, $fields, $increaseFields = array(), $bitFields = array())
    {
        if (!$fields && !$increaseFields && !$bitFields) {
            return false;
        }

        $result = DB::update($this->_bindSql('UPDATE %s SET %s WHERE %s=?', $this->table, $this->sqlMerge($fields, $increaseFields, $bitFields), $this->id), [$id]);

        SimpleHook::getInstance(get_class($this) . '_update')->runDo($id, $fields, $increaseFields);

        return $result;
    }

    protected function _batchUpdate($ids, $fields, $increaseFields = array(), $bitFields = array())
    {
        if (!$fields && !$increaseFields && !$bitFields) {
            return false;
        }

        $result = DB::update($this->_bindSql('UPDATE %s SET %s WHERE %s IN (?)', $this->table, $this->sqlMerge($fields, $increaseFields, $bitFields), $this->id), [implode(',', $ids)]);

        SimpleHook::getInstance(get_class($this) . '_batchUpdate')->runDo($ids, $fields, $increaseFields);

        return $result;
    }

    protected function _bindSql($sql)
    {
        $args = func_get_args();

        return call_user_func_array('sprintf', $args);
    }

    protected function sqlMerge($updateFields, $increaseFields, $bitFields = array())
    {
        $sql = $etr = '';

        if ($updateFields) {
            $sql .= $this->sqlSingle($updateFields);
            $etr = ',';
        }

        if ($increaseFields) {
            $sql .= $etr . $this->sqlSingleIncrease($increaseFields);
            $etr = ',';
        }

        if ($bitFields) {
            $sql .= $etr . $this->sqlSingleBit($bitFields);
        }

        return $sql;
    }

    protected function sqlSingle($array)
    {
        if (!is_array($array)) return '';

        $str = array();
        foreach ($array as $key => $val) {
            $str[] = $key . '=' . $val;
        }

        return $str ? implode(',', $str) : '';
    }

    protected function sqlSingleIncrease($array)
    {
        if (!is_array($array)) return '';

        $str = array();
        foreach ($array as $key => $val) {
            $str[] = $key . '=' . $key . '+' . $val;
        }

        return $str ? implode(',', $str) : '';
    }

    protected function sqlSingleBit($array)
    {
        if (!is_array($array)) return '';

        $str = array();
        foreach ($array as $key => $val) {
            if (!$val || !is_array($val)) continue;

            foreach ($val as $bit => $v) {
                $str[] = $key . '=' . $key . ($v ? '|' : '&~') . '(1<<' . ($bit - 1) . ')';
            }
        }

        return $str ? implode(',', $str) : '';
    }

    protected function _replace_($data)
    {
        return DB::table('REPLACE INTO ' . $this->_table . ' SET ' . $this->sqlSingle($data));
    }

    protected function _batchReplace_($data)
    {
        foreach($data as $v){
            $this->_replace_($v);
        }
    }

    protected function _batchAdd($data)
    {
        DB::table($this->table)->insert($data);
    }
}