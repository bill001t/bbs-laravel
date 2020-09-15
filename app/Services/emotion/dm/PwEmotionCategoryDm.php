<?php

namespace App\Services\emotion\dm;

use App\Core\BaseDm;

class PwEmotionCategoryDm extends BaseDm
{

    public $categoryId;

    public function __construct($categoryId = null)
    {
        isset($categoryId) && $this->categoryId = (int)$categoryId;
    }

    public function setCategoryMame($categoryname)
    {
        $this->_data['category_name'] = Tool::substrs($categoryname, 4);
        return $this;
    }

    public function setEmotionFolder($emotionFolder)
    {
        $this->_data['emotion_folder'] = $emotionFolder;
        return $this;
    }

    public function setEmotionApps($apps)
    {
        !is_array($apps) && $apps = array();
        $_apps = implode('|', $apps);
        $this->_data['emotion_apps'] = $_apps;
        return $this;
    }

    public function setOrderId($orderid)
    {
        $this->_data['orderid'] = (int)$orderid;
        return $this;
    }

    public function setIsopen($isopen)
    {
        $this->_data['isopen'] = (int)$isopen;
        return $this;
    }

    protected function _beforeAdd()
    {
        return true;
    }

    protected function _beforeUpdate()
    {
        return true;
    }


}

?>