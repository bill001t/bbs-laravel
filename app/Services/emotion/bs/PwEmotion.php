<?php

namespace App\Services\emotion\bs;

use App\Core\ErrorBag;
use App\Services\emotion\dm\PwEmotionDm;
use App\Services\emotion\ds\dao\PwEmotionDao;

class PwEmotion
{

    /**
     * 获取一条表情信息
     *
     * @param int $emotionId
     */
    public function getEmotion($emotionId)
    {
        $emotionId = (int)$emotionId;
        if ($emotionId < 1) return array();
        return $this->_getDao()->getEmotion($emotionId);
    }

    /**
     * 获取多条表情信息
     *
     * @param array $emotionIds
     */
    public function fetchEmotion($emotionIds)
    {
        if (!is_array($emotionIds) || !$emotionIds) return array();
        return $this->_getDao()->fetchEmotion($emotionIds);
    }

    /**
     * 获取多个分类的表情
     *
     * @param array $categoryIds
     */
    public function fetchEmotionByCatid($categoryIds)
    {
        if (!is_array($categoryIds) || !$categoryIds) return array();
        return $this->_getDao()->fetchEmotionByCatid($categoryIds);
    }

    /**
     * 获取一个分类的表情
     *
     * @param int $categoryId
     * @param bool $isUsed
     */
    public function getListByCatid($categoryId, $isUsed = null)
    {
        $categoryId = (int)$categoryId;
        if ($categoryId < 1) return array();
        isset($isUsed) && $isUsed = (int)$isUsed;
        return $this->_getDao()->getListByCatid($categoryId, $isUsed);
    }

    /**
     * 获取所有表情
     */
    public function getAllEmotion()
    {
        return $this->_getDao()->getAllEmotion();
    }


    public function addEmotion(PwEmotionDm $dm)
    {
        $resource = $dm->beforeAdd();
        if ($resource instanceof ErrorBag) return $resource;
        return $this->_getDao()->addEmotion($dm->getData());
    }

    public function updateEmotion(PwEmotionDm $dm)
    {
        $resource = $dm->beforeUpdate();
        if ($resource instanceof ErrorBag) return $resource;
        return $this->_getDao()->updateEmotion($dm->emotionId, $dm->getData());
    }

    public function deleteEmotion($emotionId)
    {
        $emotionId = (int)$emotionId;
        if ($emotionId < 1) return false;
        return $this->_getDao()->deleteEmotion($emotionId);
    }

    public function deleteEmotionByCatid($cateId)
    {
        if (empty($cateId)) return false;
        return $this->_getDao()->deleteEmotionByCatid($cateId);
    }

    private function _getDao()
    {
        return app(PwEmotionDao::class);
    }
}

?>