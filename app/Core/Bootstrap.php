<?php

namespace App\Core;

abstract class bootstrap
{

    public $cache;
    public $config;
    public $time;
    public $charset;
    public $url;

    public function getCache()
    {
        $cache = new PwCache();
        $cache->mergeKeys(Wekit::V('cacheKeys'));
        if (Wekit::V('dbcache') && $cache->isDbCache()) {
            PwLoader::importCache(Wekit::S('cacheService'));
        }
        return $cache;
    }

    public function getConfigBo()
    {
        return new \App\CorePwConfigBo($this->_re);
    }

    public function getTime()
    {
        return time();
    }

    public function getCharset()
    {
        return 'UTF-8';
    }

    /*public function getUrl()
    {
        $_consts = Wekit::S('publish');
        foreach ($_consts as $const => $value) {
            if (defined($const)) continue;
            if ($const === 'PUBLIC_URL' && !$value) {
                $value = Wind::getComponent('request')->getBaseUrl(true);
                if (defined('BOOT_PATH') && 0 === strpos(BOOT_PATH, PUBLIC_PATH)) {
                    $path = substr(BOOT_PATH, strlen(PUBLIC_PATH));
                    !empty($path) && $value = substr($value, 0, -strlen($path));
                }
            }
            define($const, $value);
        }
        $url = new \stdClass();
        $url->base = app()->path();
        $url->res = url(PUBLIC_RES, $url->base);
        $url->css = $url->base . '/css/', $url->base);
        $url->images = url(PUBLIC_RES . '/images/', $url->base);
        $url->js = url(PUBLIC_RES . '/js/dev/', $url->base);
        $url->attach = url(PUBLIC_ATTACH, $url->base);
        $url->themes = url(PUBLIC_THEMES, $url->base);
        $url->extres = url(PUBLIC_THEMES . '/extres/', $url->base);
        return $url;
    }*/
}
