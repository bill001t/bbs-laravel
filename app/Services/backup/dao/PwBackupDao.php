<?php

namespace App\Services\backup\dao;

use DB;
/**
 * 数据库备份还原
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 * @package wind
 */
class PwBackupDao
{

    /**
     * 获取一个表的总行数
     *
     * @param $table
     * @return table status string
     */
    public function getTableStatus($table)
    {
        return DB::select('SHOW TABLE STATUS LIKE ' . $table);
    }

    /**
     * 获取数据
     *
     * @param $table
     * @param int $start
     * @param int $limit
     * @return table status string
     */
    public function getData($table, $limit, $start)
    {
        return DB::select('SELECT * FROM ' . $table . 'limit ?, ?', [$start, $limit]);


       /* $sql = $this->_bindSql('SELECT * FROM `%s` %s ', $table, $this->sqlLimit($limit, $start));
        $smt = $this->getConnection()->createStatement($sql);
        $result = $smt->queryAll(array(), '', PDO::FETCH_NUM);
        $temp = $array = array();
        foreach ($result as $k => $v) {
            foreach ($v as $kt => $vt) {
                $temp[$kt] = $this->getConnection()->quote($vt);
            }
            $array[$k] = $temp;
        }
        return $array;*/
    }

    /**
     * 获取表的字段数
     *
     * @param $table
     * @return int
     */
    public function getColumnCount($table)
    {
        return DB::select('SELECT * FROM ' . $table . 'limit 1', []);
    }

    /**
     * 获取create table 信息
     *
     * @param $table
     * @return create table string
     */
    public function getCreateTable($table)
    {
        return DB::select('SHOW CREATE TABLE ' . $table, []);
    }

    /**
     * 获取所有表
     *
     * @return tables
     */
    public function getTables()
    {
        return DB::select('SHOW TABLES', []);
    }

    /**
     * 优化表
     *
     * @param string $tables table1,table2,table3....
     * @return tables
     */
    public function optimizeTables($table)
    {
        return DB::statement('OPTIMIZE TABLE ' . $table);
    }

    /**
     * 修复表
     *
     * @param string $tables table1,table2,table3....
     * @return tables
     */
    public function repairTables($table)
    {
        return DB::statement('REPAIR TABLE ' . $table . ' EXTENDED');
    }

    /**
     * 执行Sql
     *
     * @return tables
     */
    public function executeQuery($query)
    {
        return DB::statement($query);
    }

    /**
     * 获取表前缀
     *
     * @param $table
     * @return create table string
     */
    public function getTablePrefix()
    {
        return '_';
    }
}

?>