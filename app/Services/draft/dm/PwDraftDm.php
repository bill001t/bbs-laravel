<?php

namespace App\Services\draft\dm;

use App\Core\BaseDm;
use App\Core\ErrorBag;

/**
 * 草稿箱DM
 *
 * @author jinlong.panjl <jinlong.panjl@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$
 * @package wind
 */
class PwDraftDm extends BaseDm
{

    /**
     * 设置标题
     *
     * @param string $title
     * @return PwDraftDm
     */
    public function setTitle($title)
    {
        $this->_data['title'] = $title;
        return $this;
    }

    /**
     * 设置内容
     *
     * @param string $content
     * @return PwDraftDm
     */
    public function setContent($content)
    {
        $this->_data['content'] = $content;
        return $this;
    }

    /**
     * 设置创建人
     *
     * @param int $created_userid
     * @return PwDraftDm
     */
    public function setCreatedUserid($created_userid)
    {
        $this->_data['created_userid'] = intval($created_userid);
        return $this;
    }

    /**
     * 设置创建时间
     *
     * @param int $created_time
     * @return PwDraftDm
     */
    public function setCreatedTime($created_time)
    {
        $this->_data['created_time'] = intval($created_time);
        return $this;
    }

    /* (non-PHPdoc)
     * @see BaseDm::_beforeAdd()
     */
    protected function _beforeAdd()
    {
        return $this->check();
    }

    /* (non-PHPdoc)
     * @see BaseDm::_beforeUpdate()
     */
    protected function _beforeUpdate()
    {
        return $this->check();
    }

    /**
     * 检查数据
     *
     * @return ErrorBag
     */
    protected function check()
    {
        if (!isset($this->_data['created_userid'])) return new ErrorBag('BBS:draft.user.not.login');
        if (!isset($this->_data['title']) || !$this->_data['content']) return new ErrorBag('BBS:draft.content.empty');
        return true;
    }
}