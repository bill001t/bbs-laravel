<?php

namespace App\Services\usertag\so;

class PwUserTagSo
{
    private $_data = array();

    public function getData()
    {
        return $this->_data;
    }

    /**
     * 设置标签名字
     *
     * @param string $name
     * @return PwUserTagSo
     */
    public function setName($name)
    {
        $this->_data['name'] = trim($name);
        return $this;
    }

    /**
     * 设置是否热门
     *
     * @param string $hot
     * @return PwUserTagSo
     */
    public function setIfhot($hot)
    {
        $this->_data['ifhot'] = in_array($hot, array('0', '1')) ? intval($hot) : '';
        return $this;
    }

    /**
     * 设置最少使用的用户数
     *
     * @param int $count
     * @return PwUserTagSo
     */
    public function setMinCount($count)
    {
        $this->_data['min_count'] = strlen($count) == 0 ? '' : intval($count);
        return $this;
    }

    /**
     * 设置最多使用的用户数
     *
     * @param int $count
     * @return PwUserTagSo
     */
    public function setMaxCount($count)
    {
        $this->_data['max_count'] = strlen($count) == 0 ? '' : intval($count);
        return $this;
    }
}