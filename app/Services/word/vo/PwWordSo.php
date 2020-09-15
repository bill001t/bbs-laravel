<?php

namespace App\Services\word\vo;

class PwWordSo
{

    protected $_data = array();

    /**
     * 设置类型查询条件
     *
     * @param int $type
     * @return PwWordSo
     */
    public function setWordType($type)
    {
        $this->_data['word_type'] = $type;
        return $this;
    }

    /**
     * 设置词语查询条件
     *
     * @param string $word
     */
    public function setWord($word)
    {
        $this->_data['word'] = $word;
        return $this;
    }

    public function getData()
    {
        return $this->_data;
    }
}