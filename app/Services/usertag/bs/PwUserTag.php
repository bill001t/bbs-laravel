<?php

namespace App\Services\usertag\bs;

use App\Core\ErrorBag;
use App\Services\usertag\dm\PwUserTagDm;
use App\Services\usertag\ds\dao\PwUserTagDao;
use App\Services\usertag\so\PwUserTagSo;

class PwUserTag {

	/**
	 * 根据标签ID获得该标签的数据
	 *
	 * @param int $tag_id
	 * @return ErrorBag|array
	 */
	public function getTag($tag_id) {
		if (($tag_id = intval($tag_id)) < 1) return array();
		return $this->_getDao()->getTag($tag_id);
	}
	
	/**
	 * 根据标签ID列表批量获取标签信息
	 *
	 * @param array $tag_ids
	 * @return array
	 */
	public function fetchTag($tag_ids) {
		if (!$tag_ids) return array();
		return $this->_getDao()->fetchTag($tag_ids);
	}
	
	/**
	 * 根据标签名字获取标签
	 *
	 * @param string $name
	 * @return array
	 */
	public function getTagByName($name) {
		if (!$name) return array();
		return $this->_getDao()->getTagByName($name);
	}

	/**
	 * 分段获取热门标签
	 *
	 * @param int $limit
	 * @param int $start
	 * @return array
	 */
	public function getHotTag($limit, $start = 0) {
		return $this->_getDao()->getHotTag($limit, $start);
	}

	/**
	 * 统计热门标签
	 * 
	 * @return int
	 */
	public function countHotTag() {
		return $this->_getDao()->countHotTag();
	}

	/**
	 * 添加一个标签
	 *
	 * @param PwUserTagDm $tagDm
	 * @return int
	 */
	public function addTag(PwUserTagDm $tagDm) {
		if (true !== ($r = $tagDm->beforeAdd())) return $r;
		return $this->_getDao()->addTag($tagDm->getData());
	}

	/**
	 * 批量添加标签
	 *
	 * @param array $tagDms
	 * @return ErrorBag|boolean
	 */
	public function batchAddTag($tagDms) {
		$data = array();
		foreach ($tagDms as $_item) {
			if (!$_item instanceof PwUserTagDm) return new ErrorBag('USER:tag.illega.format');
			if (true !== ($r = $_item->beforeAdd())) return $r;
			$data[] = $_item->getData();
		}
		if (!$data) return false;
		return $this->_getDao()->batchAddTag($data);
	}

	/**
	 * 更新标签
	 *
	 * @param PwUserTagDm $tagDm
	 * @return boolean
	 */
	public function updateTag(PwUserTagDm $tagDm) {
		if (true !== ($r = $tagDm->beforeUpdate())) return $r;
		return $this->_getDao()->updateTag($tagDm->tag_id, $tagDm->getData(), 
			$tagDm->getIncreaseData());
	}

	/**
	 * 批量设置标签的热门
	 *
	 * @param array $tag_ids
	 * @param int $ifhot
	 * @return ErrorBag|boolean
	 */
	public function batchUpdateTagHot($tag_ids, $ifhot) {
		if (empty($tag_ids)) return new ErrorBag('USER:tag.id.require');
		return $this->_getDao()->batchUpdateTag($tag_ids, intval($ifhot));
	}

	/**
	 * 删除标签
	 *
	 * @param int $tag_id
	 * @return boolean
	 */
	public function deleteTag($tag_id) {
		if (($tag_id = intval($tag_id)) < 1) return new ErrorBag('USER:tag.id.require');
		return $this->_getDao()->deleteTag($tag_id);
	}

	/**
	 * 批量删除标签
	 *
	 * @param array $tag_ids
	 * @return ErrorBag|boolean
	 */
	public function batchDeleteTag($tag_ids) {
		if (empty($tag_ids)) return new ErrorBag('USER:tag.id.require');
		return $this->_getDao()->batchDeleteTag($tag_ids);
	}

	/**
	 * 搜索个人标签
	 *
	 * @param PwUserTagSo $tagSo
	 * @param int $limit
	 * @param int $start
	 * @return array
	 */
	public function searchTag(PwUserTagSo $tagSo, $limit, $start = 0) {
		return $this->_getDao()->searchTag($tagSo->getData(), $limit, $start);
	}

	/**
	 * 统计搜索的个人标签
	 *
	 * @param PwUserTagSo $tagSo
	 * @return int
	 */
	public function countSearchTag(PwUserTagSo $tagSo) {
		return $this->_getDao()->countSearchTag($tagSo->getData());
	}

	/**
	 * 获得用户标签DAO
	 * 
	 * @return PwUserTagDao
	 */
	private function _getDao() {
		return app(PwUserTagDao::class);
	}
}