<?php
defined('WEKIT_VERSION') || exit('Forbidden');

/**
 * the last known user to change this file in the repository  <$LastChangedBy$>
 * @author $Author$ Foxsee@aliyun.com
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id$ 
 * @package 
 */

class PwLikeDoReplyInjector extends PwBaseHookInjector {
	
	public function run() {
		$from = $request->get('from_type', 'post');
		$pid = (int)$request->get('pid', 'post');
		$tid = (int)$request->get('tid', 'post');
		if ($from != 'like') return true;
		if ($pid < 1 && $tid < 1) return true;
		$ds = app('like.PwLikeContent');
		if ($pid) {
			$info = $ds->getInfoByTypeidFromid(PwLikeContent::POST, $pid);
		} else {
			$info = $ds->getInfoByTypeidFromid(PwLikeContent::THREAD, $tid);
		}
		if (!isset($info['likeid'])) return true;
		Wind::import('SRV:like.srv.reply.do.PwLikeDoReply');
		return new PwLikeDoReply($info['likeid']);
	}


}