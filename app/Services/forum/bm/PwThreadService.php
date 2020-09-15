<?php
namespace App\Services\forum\bm;

use App\Services\user\bs\PwUser;
use App\Services\word\bm\PwWordFilter;
use App\Core\Tool;

/**
 * 帖子公共服务
 */

class PwThreadService
{
	public function displayReplylist($replies, $contentLength = 140) {
		$users = app(PwUser::class)->fetchUserByUid(array_unique($replies->pluck('created_userid')->all()));

		foreach ($replies as $key => $value) {
			$value['content'] = Tool::escapeHTML($value['content']);
			if (!empty($value['ifshield'])) {
				$value['content'] = '<div class="shield">此帖已被屏蔽</div>';
			} elseif ($users[$value['created_userid']]['groupid'] == '6') {
				$value['content'] = '用户被禁言,该主题自动屏蔽!';
			} else {
				$value['content'] = Tool::substrs($value['content'], $contentLength);
			}

			!$value['word_version'] && $value['content'] = app(PwWordFilter::class)->replaceWord($value['content'], $value['word_version']);

			$replies[$key] = $value;
		}
		return $replies;
	}

	public function displayContent($content, $contentLength = 140) {
		$content = Tool::escapeHTML($content);
		$content = Tool::substrs($content, $contentLength);

		return $content;
	}
}