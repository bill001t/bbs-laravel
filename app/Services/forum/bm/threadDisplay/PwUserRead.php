<?php

namespace App\Services\forum\bm\threadDisplay;

use App\Services\forum\bm\threadDisplay\PwReadDataSource;

/**
 * 帖子内容页回复列表数据接口 / 只看某用户列表|只看楼主
 */

class PwUserRead extends PwReadDataSource
{
	
	public $thread;
	public $info;
	public $tid;
	public $uid;

	public function __construct(PwThreadBo $thread, $uid) {
		$this->thread = $thread;
		$this->tid = $thread->tid;
		$this->uid = $uid;
		$this->info =& $thread->info;
		$this->urlArgs['uid'] = $uid;
	}

	public function initPage($total) {
		$this->maxpage = ceil($total / $this->perpage);
		$this->page < 1 && $this->page = 1;
		$this->page > $this->maxpage && $this->page = $this->maxpage;
	}
	
	public function execute() {
		$this->total = app('forum.PwThread')->countPostByTidAndUid($this->tid, $this->uid) + 1;
		$this->initPage($this->total);
		
		list($start, $limit) = Tool::page2limit($this->page, $this->perpage);
		if ($start == 0) {
			$this->info['pid'] = 0;
			$this->data[] =& $this->info;	//地址引用，便于bulidRead同步修改
			$this->info['aids'] && ($this->_aids[] = 0);
			$this->_uids[] = $this->info['created_userid'];
		}
		if ($this->total > 1) {
			$offset = $start;
			$offset == 0 ? $limit-- : $offset--;
			$replies = app('forum.PwThread')->getPostByTidAndUid($this->tid, $this->uid, $limit, $offset, $this->asc);
			foreach ($replies as $value) {
				$this->data[] = $value;
				$value['aids'] && ($this->_aids[] = $value['pid']);
				$this->_uids[] = $value['created_userid'];
			}
		}
		$this->firstFloor = $start;
	}
}