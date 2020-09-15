<?php

namespace App\Services\attention\bs;

use App\Services\attention\ds\dao\PwAttentionDao;
use App\Services\attention\ds\dao\PwAttentionRecommendFriendsDao;

class PwAttentionRecommendFriends
{

    public function getRecommendFriend($uid, $limit, $offset = 0)
    {
        $uid = intval($uid);
        if ($uid < 1) return array();
        return $this->_getDao()->get($uid, $limit, $offset);
    }

    public function getSameUser($uid, $recommendUid)
    {
        $uid = intval($uid);
        $recommendUid = intval($recommendUid);
        if ($uid < 1 || $recommendUid < 1) return array();
        return $this->_getDao()->getSameUser($uid, $recommendUid);
    }

    public function addRecommendFriend($uid, $recommendUid, $recommend_user)
    {
        if (!$uid || !$recommendUid || $uid == $recommendUid) return false;

        return $this->_getDao()->replace(array(
            'uid' => $uid,
            'recommend_uid' => $recommendUid,
            'recommend_user' => $recommend_user
        ));
    }

    public function batchReplaceRecommendFriend($data)
    {
        if (!$data || !is_array($data)) return false;
        return $this->_getDao()->batchReplace($data);
    }

    /**
     *
     * 删除某用户的潜在好友
     * @param int $uid
     * @param int $recommendUid
     */
    public function deleteRecommendFriend($uid, $recommendUid = 0)
    {
        $uid = intval($uid);
        $recommendUid = intval($recommendUid);
        if ($uid < 1) return false;
        if ($recommendUid) {
            return $this->_getDao()->deleteByRecommend($uid, $recommendUid);
        } else {
            return $this->_getDao()->_delete($uid);
        }
    }

    /**
     *
     * @return PwAttentionRecommendFriendsDao
     */
    private function _getDao()
    {
        return app(PwAttentionRecommendFriendsDao::class);
    }

    /**
     *
     * @return PwAttentionDao
     */
    private function _getAttentionDao()
    {
        return app(PwAttentionDao::class);
    }
}