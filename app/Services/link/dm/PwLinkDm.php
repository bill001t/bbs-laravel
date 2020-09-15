<?php

namespace App\Services\link\dm;

use App\Core\BaseDm;
use App\Core\ErrorBag;
use App\Core\Tool;

class PwLinkDm extends BaseDm
{

    private $lid;

    public function __construct($lid = 0)
    {
        $lid && $this->lid = $lid;
    }

    /**
     * 设置顺序
     *
     * @param int $vieworder
     * @return PwLinkDm
     */
    public function setVieworder($vieworder)
    {
        $this->_data['vieworder'] = intval($vieworder);
        return $this;
    }

    /**
     * 设置名称
     *
     * @param string $name
     * @return PwLinkDm
     */
    public function setName($name)
    {
        $this->_data['name'] = $name;
        return $this;
    }

    /**
     * 设置url链接
     *
     * @param string $url
     * @return PwLinkDm
     */
    public function setUrl($url)
    {
        $this->_data['url'] = ($url && substr($url, 0, 4) != 'http') ? 'http://' . $url : $url;
        return $this;
    }

    /**
     * 设置友情链接描述
     *
     * @param string $descrip
     * @return PwLinkDm
     */
    public function setDescrip($descrip)
    {
        $this->_data['descrip'] = $descrip;
        return $this;
    }

    /**
     * 设置友情链接logo
     *
     * @param string $logo
     * @return PwLinkDm
     */
    public function setLogo($logo)
    {
        $this->_data['logo'] = $logo;
        return $this;
    }

    /**
     * 设置友情链接iflogo
     *
     * @param int $iflogo
     * @return PwLinkDm
     */
    public function setIflogo($iflogo)
    {
        $this->_data['iflogo'] = intval($iflogo);
        return $this;
    }

    /**
     * 设置友情链接是否启用  1启用 | 0非
     *
     * @param int $ifcheck
     * @return PwLinkDm
     */
    public function setIfcheck($ifcheck)
    {
        $this->_data['ifcheck'] = intval($ifcheck);
        return $this;
    }

    /**
     * 设置联系方式
     *
     * @param string $contact
     * @return PwLinkDm
     */
    public function setContact($contact)
    {
        $this->_data['contact'] = $contact;
        return $this;
    }

    /**
     * 设置类型
     *
     * @param int $typeid
     * @return PwLinkDm
     */
    public function setType($typeid)
    {
        $this->_data['typeid'] = intval($typeid);
        return $this;
    }

    /**
     * 获取类型
     *
     * @return array
     */
    public function getType()
    {
        return $this->_data['typeid'];
    }

    /**
     * 获取lid
     *
     * @return array
     */
    public function getLid()
    {
        return $this->lid;
    }

    protected function _beforeUpdate()
    {
        if (isset($this->_data['url']) && !$this->_data['url']) {
            return new ErrorBag('LINK:require_empty');
        }
        if (isset($this->_data['name'])) {
            $len = Tool::strlen($this->_data['name']);

            if ($len < 1 || $len > 15) {

                return new ErrorBag('LINK:link.lenerror');

            }
        }
        return true;
    }

    protected function _beforeAdd()
    {
        if (!$this->_data['name']) {
            return new ErrorBag('LINK:require_empty');
        }
        if (!$this->_data['url']) {
            return new ErrorBag('LINK:require_empty');
        }
        $len = Tool::strlen($this->_data['name']);
        if ($len < 1 || $len > 15) {
            return new ErrorBag('LINK:link.lenerror');
        }
        return true;
    }
}

?>