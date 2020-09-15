<?php

namespace App\Services\emotion\bm;

use Core;
use App\Services\emotion\bs\PwEmotion;

class PwEmotionService
{

    private $_cacheKey = 'all_emotions';

    /**
     * 更新表情缓存
     *
     */
    public function updateCache()
    {
        Core::cache()->set($this->_cacheKey, $this->getAllEmotionNoCache());
        return true;
    }

    /**
     * 从缓存读取所有表情
     *
     */
    public function getAllEmotion()
    {
        return Core::cache()->get($this->_cacheKey);
    }

    /**
     * 从数据库读取所有表情
     *
     */
    public function getAllEmotionNoCache()
    {
        $list = app(PwEmotion::class)->getAllEmotion();
        $emotions = array(
            'emotion' => array(),
            'name' => array(),
        );

        foreach ($list as $v) {
            $tmp['emotion_folder'] = $v['emotion_folder'];
            $tmp['emotion_icon'] = $v['emotion_icon'];
            $emotions['emotion'][$v['emotion_id']] = $tmp;
            $v['emotion_name'] && $emotions['name'][$v['emotion_name']] = $v['emotion_id'];
        }

        return $emotions;
    }

    /**
     * 读取表情文件夹
     *
     */
    public function getFolderList()
    {
        return WindFolder::read($this->getEmotionPath(), WindFolder::READ_DIR);
    }

    /**
     * 读取表情列表
     *
     * @param stting $folder
     */
    public function getFolderIconList($folder)
    {
        $folder = $this->getEmotionPath() . '/' . $folder;
        return WindFolder::read($folder, WindFolder::READ_FILE);
    }

    /**
     * 表情应用场景
     *
     * @param string $select
     */
    public function getAppcationList($select = 0)
    {
        //$apps = array('bbs'=>'论坛', 'weibo'=>'微博', 'cms'=>'门户' , 'face'=>'普通表情');
        $apps = array('bbs' => '论坛');
        return $select ? $apps[$select] : $apps;
    }

    public function getEmotionPath()
    {
        return Wind::getRealDir('PUBLIC:res.images.emotion');

    }

    /**
     * PwEmotion
     *
     * @return PwEmotion
     */
    private function _getEmotionDs()
    {
        return app(PwEmotion::class);
    }
}

?>