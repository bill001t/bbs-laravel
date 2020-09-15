<?php
namespace App\Services\forum\bm\threadList;

/**
 * 帖子列表数据接口
 *
 */

abstract class PwThreadDataSource
{
	protected $urlArgs = array();

	/**
	 * 获取帖子统计数
	 *
	 * @return int
	 */
	abstract public function getTotal();
	
	/**
	 * 获取帖子
	 *
	 * @param int $limit 获取条目
	 * @param int $offset 帖子起始偏移量
	 * @return array
	 */
	abstract public function getData($perpage, $offset = '');
	
	/**
	 * 设置url参数
	 *
	 * @param string $key
	 * @param string $value
	 */
	public function setUrlArg($key, $value) {
		$this->urlArgs[$key] = $value;
	}

	/**
	 * 获取当前链接模式
	 *
	 * @return string
	 */
	public function getUrlArgs() {
		return $this->urlArgs;
// 		return $this->urlArgs ? http_build_query($this->urlArgs) : '';
	}
}