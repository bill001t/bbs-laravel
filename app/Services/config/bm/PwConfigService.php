<?php

namespace App\Services\config\bm;

use App\Services\cache\bm\PwCacheUpdateService;

/**
 * 通用配置服务
 */
class PwConfigService
{
	/**
	 * 当任意配置项被修改时，调用该服务更新缓存文件(Hook调用)
	 *
	 * @param string $namespace
	 */
	public function updateConfig($namespace) {
		if (in_array($namespace, array('site', 'credit', 'bbs', 'attachment', 'components', 'seo', 'nav', 'windid'))) {
			app(PwCacheUpdateService::class)->updateConfig;
		}
	}
}