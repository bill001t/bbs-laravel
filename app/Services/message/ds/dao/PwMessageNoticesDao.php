<?php

namespace App\Services\message\ds\dao;

use App\Services\message\ds\relation\MessageNotice;

class PwMessageNoticesDao extends MessageNotice
{
    public function getNotice($id)
    {
        return self::find($id);
    }

    public function getPrevNotice($uid, $id)
    {
        return self::where('uid', $uid)
            ->where('id', '<', $id)
            ->orderby('id', 'desc')
            ->first();
    }

    public function getNextNotice($uid, $id)
    {
        return self::where('uid', $uid)
            ->where('id', '>', $id)
            ->orderby('id', 'asc')
            ->first();
    }

    public function getNoticesOrderByRead($uid, $num)
    {
        return self::where('uid', $uid)
            ->orderby('is_read', 'asc')
            ->orderby('modified_time', 'desc')
            ->paginate(intval($num));
    }

    public function getNotices($uid, $typeid, $offset = 0, $num = 20)
    {
        $offset = intval($offset);
        $num = intval($num);
        $typeid = intval($typeid);
        $params = array($uid);
        $sql = self::where('uid', $uid);
        if ($typeid > 1) {
            $sql = $sql->where('typeid', $typeid);
        } else {
            $sql = $sql->where('typeid', '>', 1);
        }
        return $sql->orderby('modified_time', 'DESC')
            ->paginate($num);
    }

    /**
     * 获取未读通知数
     * @param int $uid
     * @return int
     */
    public function getUnreadNoticeCount($uid)
    {
        return self::where('uid', $uid)
            ->where('is_read', 0)
            ->count();
    }

    public function addNotice($fields)
    {
        self::create($fields);
    }

    /**
     *
     * 获取用户通知(按类型)
     * @param int $uid
     * @param int $type
     * @param int $param
     */
    public function getNoticeByUid($uid, $type, $param)
    {
        return self::where('uid', $uid)
            ->where('typeid', $type)
            ->where('param', $param)
            ->get();
    }

    /**
     *
     * 按类型统计用户通知数
     * @param int $uid
     */
    public function countNoticesByType($uid)
    {
        return self::select(DB::Raw('COUNT(*) AS num,typeid'))
            ->where('uid', $uid)
            ->where('typeid', '>', 1)
            ->groupby('typeid')
            ->get();

    }

    public function updateNotice($id, $fields, $increaseFields = array())
    {
        foreach($increaseFields as $k => $v){
            self::where('id', $id)
                ->increment($k, $v);
        }

        return self::where('id', $id)
            ->update($fields);
    }

    public function batchUpdateNotice($ids, $fields, $increaseFields = array())
    {
        foreach($increaseFields as $k => $v){
            self::whereIn('id', $ids)
                ->increment($k, $v);
        }

        return self::whereIn('id', $ids)
            ->update($fields);
    }

    public function batchUpdateNoticeByUidAndType($uid, $type, $fields)
    {
        return self::where('uid', $uid)
            ->where('typeid', $type)
            ->update($fields);
    }

    /**
     *
     * 删除一条通知
     * @param int $id
     */
    public function deleteNotice($id)
    {
        return self::destroy($id);
    }

    /**
     *
     * 批量删除通知
     * @param int $id
     */
    public function deleteNoticeByIds($ids)
    {
        return self::destroy($ids);

    }

    public function deleteNoticeByIdsAndUid($uid, $ids)
    {
        return self::where('uid', $uid)
            ->whereIn('id', $ids)
            ->delete();
    }

    /**
     * 根据类型删除通知
     *
     * @param int $uid
     * @param int $type
     * @param int $param
     * @param bool
     */
    public function deleteNoticeByType($uid, $type, $param)
    {
        return self::where('uid', $uid)
            ->where('typeid', $type)
            ->where('param', $param)
            ->delete();
    }

    /**
     * 根据uid删除通知
     *
     * @param int $uid
     * @param bool
     */
    public function deleteNoticeByUid($uid)
    {
        return self::where('uid', $uid)
            ->delete();
    }

    /**
     * 根据类型批量删除通知
     *
     * @param int $uid
     * @param int $type
     * @param array $params
     * @param bool
     */
    public function betchDeleteNoticeByType($uid, $type, $params)
    {
        return self::where('uid', $uid)
            ->where('typeid', $type)
            ->whereIn('param', $params)
            ->delete();
    }
}