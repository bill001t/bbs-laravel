<?php

namespace App\Services\seo\dm;

use App\Core\BaseDm;
use App\Core\ErrorBag;

/**
 * seo的数据模型
 *
 * @author Shi Long <long.shi@alibaba-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: PwSeoDm.php 6118 2012-03-16 10:40:35Z long.shi $
 * @package service.seo.dm
 */
class PwSeoDm extends BaseDm
{

    public function setMod($mod)
    {
        $this->_data['mod'] = trim($mod);
        return $this;
    }

    public function setPage($page)
    {
        $this->_data['page'] = trim($page);
        return $this;
    }

    public function setParam($param)
    {
        $this->_data['param'] = $param ? $param : 0;
        return $this;
    }

    public function setTitle($title)
    {
        $this->_data['title'] = trim($title);
        return $this;
    }

    public function setKeywords($keywords)
    {
        $this->_data['keywords'] = trim($keywords);
        return $this;
    }

    public function setDescription($description)
    {
        $this->_data['description'] = trim($description);
        return $this;
    }

    /* (non-PHPdoc)
     * @see BaseDm::_beforeAdd()
     */
    protected function _beforeAdd()
    {
        return true;
    }

    /* (non-PHPdoc)
     * @see BaseDm::_beforeUpdate()
     */
    protected function _beforeUpdate()
    {
        if (!isset($this->_data['mod'])) return new ErrorBag('SEO:mod.empty');
        if (!isset($this->_data['page'])) return new ErrorBag('SEO:page.empty');
        if (!isset($this->_data['param'])) return new ErrorBag('SEO:param.empty');
        return true;
    }
}

?>