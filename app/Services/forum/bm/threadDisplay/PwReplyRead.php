<?php

namespace App\Services\forum\bm\threadDisplay;

use App\Services\forum\bm\threadDisplay\PwReadDataSource;

/**
 * 帖子内容页回复列表数据接口 / 普通列表
 */

class PwReplyRead extends PwReadDataSource
{
	
	public $tid;
	public $pid;

	public function __construct($tid, $pid) {
		$this->tid = $tid;
		$this->pid = $pid;
	}
	
	public function execute() {
		$value = app('forum.PwThread')->getPost($this->pid);
		$this->data[] = $value;
		$value['aids'] && ($this->_aids[] = $value['pid']);
		$this->_uids[] = $value['created_userid'];
		$this->firstFloor = app('forum.PwThread')->countPostByTidUnderPid($this->tid, $this->pid) + 1;
	}
}