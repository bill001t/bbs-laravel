<?php

namespace App\Services\emotion\bs;

use App\Core\ErrorBag;
use App\Services\emotion\dm\PwEmotionCategoryDm;
use App\Services\emotion\ds\dao\PwEmotionCategoryDao;

class PwEmotionCategory
{

    /**
     * 获取一条分类信息
     *
     * @param int $categoryId
     */
    public function getCategory($categoryId)
    {
        $categoryId = (int)$categoryId;
        if ($categoryId < 1) return array();
        return $this->_getDao()->getCategory($categoryId);
    }

    /**
     * 获取多条分类信息
     *
     * @param array $categoryIds
     */
    public function fetchCategory($categoryIds)
    {
        if (!is_array($categoryIds) || !$categoryIds) return array();
        return $this->_getDao()->fetchCategory($categoryIds);
    }

    /**
     * 获取分类列表
     *
     * @param string $app
     * @param bool $isOpen
     */
    public function getCategoryList($app = '', $isOpen = null)
    {
        isset($isOpen) && $isOpen = (int)$isOpen;
        return $this->_getDao()->getCategoryList($app, $isOpen);
    }

    public function addCategory(PwEmotionCategoryDm $dm)
    {
        $resource = $dm->beforeAdd();
        if ($resource instanceof ErrorBag) return $resource;
        return $this->_getDao()->addCategory($dm->getData());
    }

    public function updateCategory(PwEmotionCategoryDm $dm)
    {
        $resource = $dm->beforeUpdate();
        if ($resource instanceof ErrorBag) return $resource;
        return $this->_getDao()->updateCategory($dm->categoryId, $dm->getData());
    }

    public function deleteCategory($categoryId)
    {
        $categoryId = (int)$categoryId;
        if ($categoryId < 1) return false;
        return $this->_getDao()->deleteCategory($categoryId);
    }

    private function _getDao()
    {
        return app(PwEmotionCategoryDao::class);
    }
}

?>