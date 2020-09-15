<?php

namespace App\Services\seo\bm;

use App\Core\config\PwConfigSet;
use App\Core\ErrorBag;
use App\Services\seo\bs\PwSeo;
use Core;

/**
 * 对seo部署缓存策略
 *
 * @author Shi Long <long.shi@alibaba-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: PwSeoService.php 24341 2013-01-29 03:08:55Z jieyin $
 * @package wind
 */
class PwSeoService
{

    /**
     * 从缓存里取seo数据
     *
     * @param string $mod
     * @param string $page
     * @param string $param
     * @return array
     */
    public function getByModAndPageAndParamWithCache($mod, $page, $param)
    {
        $key = $this->_buildKey($mod, $page, $param);
        return Core::C()->seo->get($key, array());
        /* if (!$result) {
            $result = $this->_seoDs()->getByModAndPageAndParam($mod, $page, $param);
            Core::C()->setConfig('seo', $key, $result);
        }
        return $result; */
    }

    /**
     * 更新seo，同时更新缓存
     *
     * @param array $dms
     * @return boolean|ErrorBag
     */
    public function batchReplaceSeoWithCache($dms)
    {
        if (empty($dms)) return false;
        !is_array($dms) && $dms = array($dms);
        $r = $this->_seoDs()->batchReplaceSeo($dms);
        if ($r instanceof ErrorBag) return $r;
        $bo = new PwConfigSet('seo');
        foreach ($dms as $dm) {
            $key = $this->_buildKey($dm->getField('mod'), $dm->getField('page'), $dm->getField('param'));
            $bo->set($key, $dm->getData());
        }
        $bo->flush();
        return true;
    }

    private function _buildKey($mod, $page, $param)
    {
        return sprintf('seo_%s_%s_%d', $mod, $page, intval($param));
    }

    /**
     * @return PwSeo
     */
    private function _seoDs()
    {
        return app(PwSeo::class);
    }
}

?>