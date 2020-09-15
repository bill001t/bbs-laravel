<?php

namespace App\Services\like\bs;

use App\Core\ErrorBag;
use App\Services\like\dm\PwLikeTagDm;
use App\Services\like\ds\dao\PwLikeTagDao;

class PwLikeTag
{

    /**
     * 获取一条内容
     *
     * @param int $tagid
     */
    public function getLikeTag($tagid)
    {
        $tagid = (int)$tagid;
        if ($tagid < 1) return array();
        return $this->_getLikeTagDao()->getInfo($tagid);
    }

    /**
     * 批量获取内容
     *
     * @param array $tagids
     */
    public function fetchLikeTag($tagids)
    {
        if (!is_array($tagids) || count($tagids) < 1) return array();
        return $this->_getLikeTagDao()->getInfoByTags($tagids);
    }

    /**
     * 根据用户ID获取内容
     *
     * @param int $uid
     */
    public function getInfoByUid($uid)
    {
        $uid = (int)$uid;
        if ($uid < 1) return array();
        return $this->_getLikeTagDao()->getInfoByUid($uid);
    }

    /**
     * 添加内容
     *
     * @param PwLikeTagDm $dm
     */
    public function addInfo(PwLikeTagDm $dm)
    {
        $resource = $dm->beforeAdd();
        if ($resource instanceof ErrorBag) return $resource;
        return $this->_getLikeTagDao()->addInfo($dm->getData());
    }

    /**
     * 修改内容
     *
     * @param int $tagid
     * @param PwLikeTagDm $dm
     */
    public function updateInfo(PwLikeTagDm $dm)
    {
        $resource = $dm->beforeUpdate();
        if ($resource instanceof ErrorBag) return $resource;
        return $this->_getLikeTagDao()->updateInfo($dm->tagid, $dm->getData());
    }

    /**
     * 更新统计
     *
     * @param int $tagid
     * @param bool $type true +1  false -1
     */
    public function updateNumber($tagid, $type = true)
    {
        $tagid = (int)$tagid;
        if ($tagid < 1) return false;
        return $this->_getLikeTagDao()->updateNumber($tagid, $type);
    }

    /**
     * 删除内容
     *
     * @param int $tagid
     */
    public function deleteInfo($tagid)
    {
        $tagid = (int)$tagid;
        if ($tagid < 1) return false;
        return $this->_getLikeTagDao()->deleteInfo($tagid);
    }

    private function _getLikeTagDao()
    {
        return app(PwLikeTagDao::class);
    }

}

?>