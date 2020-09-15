<?php

namespace App\Services\Api\school\vo;

class SchoolSo
{
    private $_data = array();

    /**
     * 搜索学校名
     *
     * @param string $name
     * @return WindidSchoolSo
     */
    public function setName($name)
    {
        $this->_data['name'] = $name;
        return $this;
    }

    /**
     * 搜索类型小学1；中学2；大学3
     *
     * @param int $typeid
     * @return WindidSchoolSo
     */
    public function setTypeid($typeid)
    {
        $this->_data['typeid'] = intval($typeid);
        return $this;
    }

    /**
     * 根据地区搜索学校
     *
     * @param int $areaid
     * @return WindidSchoolSo
     */
    public function setAreaid($areaid)
    {
        $this->_data['areaid'] = intval($areaid);
        return $this;
    }

    /**
     * 根据第一个字母来搜索
     *
     * @param string $first_char
     * @return WindidSchoolSo
     */
    public function setFirstChar($first_char)
    {
        $this->_data['first_char'] = trim($first_char);
        return $this;
    }

    /**
     * 返回搜索的条件数组
     *
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }
}