<?php

namespace App\Services\word\dm;

use App\Core\BaseDm;
use App\Core\ErrorBag;
use App\Core\Tool;

class PwWordDm extends BaseDm
{

    public $id;

    public function __construct($id = 0)
    {
        $this->id = $id;
    }

    /**
     * 设置类型
     *
     * @param int $type
     * @return PwWordFilterDm
     */
    public function setWordType($type)
    {
        $this->_data['word_type'] = intval($type);
        return $this;
    }

    /**
     * 设置词语(敏感词)
     *
     * @param string $word
     * @return PwWordFilterDm
     */
    public function setWord($word)
    {
        $this->_data['word'] = trim($word);
        return $this;
    }

    /**
     * 设置替换词
     *
     * @param string $wordReplace
     * @return PwWordFilterDm
     */
    public function setWordReplace($wordReplace)
    {
        $this->_data['word_replace'] = $wordReplace;
        return $this;
    }

    /**
     * 设置词语来源
     *
     * @param string $isCustom
     * @return PwWordFilterDm
     */
    public function setWordFrom($from)
    {
        $this->_data['word_from'] = $from;
        return $this;
    }

    protected function _beforeAdd()
    {
        $this->_data['created_time'] = Tool::getTime();
        return $this->_check();
    }

    protected function _beforeUpdate()
    {
        return true;
    }

    /**
     * 验证数据
     *
     * @return TRUE OR ErrorBag
     */
    private function _check()
    {
        if (empty($this->_data['word'])) {
            return new ErrorBag('WORD:word.empty');
        }

        return true;
    }
}