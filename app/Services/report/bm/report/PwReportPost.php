<?php
Wind::import('SRV:report.srv.report.PwReportAction');

class PwReportPost extends PwReportAction{
	
	protected $fid = 0;
	
	public function buildDm($type_id) {
		$threadDs = app('forum.PwThread');
		$result = $threadDs->getPost($type_id);
		if (!$result) {
			return false;
		}
		$content = Tool::substrs($result['content'], 20);
		$hrefUrl = url('bbs/read/run',array('tid' => $result['tid'], 'fid' => $result['fid']),$result['pid']);
		$this->fid = $result['fid'];
		$dm = new PwReportDm();
		$dm->setContent($content)
			->setContentUrl($hrefUrl)
			->setAuthorUserid($result['created_userid']);
		return $dm;
	}
	
	public function getExtendReceiver() {
		$forumDs = app('forum.PwForum');
		$forumInfo = $forumDs->getForum($this->fid);
		$manager = explode(',', $forumInfo['manager']);
		return array_keys($this->_getUserDs()->fetchUserByName($manager));
	}
	
	/**
	 * @return PwUser
	 */
	protected function _getUserDs(){
		return app('user.PwUser');
	}
}