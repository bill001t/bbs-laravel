<?php

namespace App\Services\like\dm;

use App\Core\BaseDm;
use App\Core\ErrorBag;
use App\Core\Tool;

class PwLikeTagDm extends BaseDm
{
    public $tagid;

    public function __construct($tagid = null)
    {
        if (isset($tagid)) $this->tagid = (int)$tagid;
    }

    public function setUid($uid)
    {
        $this->_data['uid'] = intval($uid);
        return $this;
    }

    public function setTagname($tagname)
    {
        $this->_data['tagname'] = Tool::substrs($tagname, 10);
        return $this;
    }

    public function setNumber($number)
    {
        $this->_data['number'] = intval($number);
        return $this;
    }

    protected function _beforeAdd()
    {
        if (empty($this->_data['tagname'])) return new ErrorBag('BBS:like.tagname.empty');
        return true;
    }

    protected function _beforeUpdate()
    {
        if ($this->tagid < 1) return new ErrorBag('BBS:like.tagid.empty');
        if (empty($this->_data['tagname'])) return new ErrorBag('BBS:like.tagname.not.empty');
        return true;
    }
}

?>