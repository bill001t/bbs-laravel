<?php

namespace App\Services\emotion\dm;

use App\Core\BaseDm;
use App\Core\ErrorBag;

class PwEmotionDm extends BaseDm
{

    public $emotionId;

    public function __construct($emotionId = null)
    {
        isset($emotionId) && $this->emotionId = (int)$emotionId;
    }

    public function setCategoryId($categoryid)
    {
        $this->_data['category_id'] = (int)$categoryid;
        return $this;
    }

    public function setEmotionName($emotionName)
    {
        $this->_data['emotion_name'] = Tool::substrs($emotionName, 10);
        return $this;
    }

    public function setEmotionFolder($emotionFolder)
    {
        $this->_data['emotion_folder'] = $emotionFolder;
        return $this;
    }

    public function setEmotionIcon($emotionIcon)
    {
        $this->_data['emotion_icon'] = $emotionIcon;
        return $this;
    }

    public function setVieworder($vieworder)
    {
        $this->_data['vieworder'] = (int)$vieworder;
        return $this;
    }

    public function setIsused($isused)
    {
        $this->_data['isused'] = (int)$isused;
        return $this;
    }

    protected function _beforeAdd()
    {
        return true;
    }

    protected function _beforeUpdate()
    {
        if ($this->emotionId < 1) return new ErrorBag('ADMIN:fail');
        return true;
    }


}

?>