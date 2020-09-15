<?php

namespace App\Services\Api\message\ds\dao;

use App\Services\Api\message\ds\relation\MessageDialog;
use DB;

class WindidMessageDialogDao extends MessageDialog
{
    /**
     * 获取一条
     *
     * @param int $dialogId
     * @return array
     */
    public function getDialog($dialogId)
    {
        return self::find($dialogId);
    }

    /**
     * 根据uid获取一条
     *
     * @param int $toUid
     * @param int $fromUid
     * @return array
     */
    public function getDialogByUid($toUid, $fromUid)
    {
        return self::where('to_uid', $toUid)
            ->where('from_uid', $fromUid)
            ->get();
    }

    /**
     * 获取多条
     *
     * @param int $uid
     * @param array $fromUids
     * @return array
     */
    public function getDialogByUids($uid, $fromUids)
    {
        return self::where('to_uid', $uid)
            ->whereIn('from_uid', $fromUids)
            ->get();
    }

    /**
     * 获取多条未读对话
     *
     * @param int $uid
     * @param int $limit
     * @return array
     */
    public function getUnreadDialogsByUid($uid, $limit)
    {
        return self::where('to_uid', $uid)
            ->whereIn('unread_count', '>', '0')
            ->orderby('modified_time', 'desc')
            ->paginate($limit);
    }

    /**
     * 添加消息聚合
     *
     * @param array $fields
     * @return bool
     */
    public function addDialog($fields)
    {
        return self::create($fields);
    }

    /**
     * 更新对话表
     *
     * @param int $dialogId
     * @param array $fields
     * @param array $increaseFields
     * @return bool
     */
    public function updateDialog($dialogId, $fields = array(), $increaseFields = array())
    {
        foreach ($increaseFields as $k => $v) {
            self::where('dialog_id', $dialogId)
                ->increment($k, $v);
        }

        return self::where('dialog_id', $dialogId)
            ->update($fields);
    }

    /**
     * 批量更新对话表
     *
     * @param int $dialogId
     * @param array $fields
     * @param array $increaseFields
     * @return bool
     */
    public function batchUpdateDialog($dialogIds, $fields = array(), $increaseFields = array())
    {
        foreach ($increaseFields as $k => $v) {
            self::whereIn('dialog_id', $dialogIds)
                ->increment($k, $v);
        }

        return self::whereIn('dialog_id', $dialogIds)
            ->update($fields);
    }

    /**
     * 统计用户私信数量
     *
     * @param int $uid
     * @return array
     */
    public function countUserMessages($uid)
    {
        return self::select(DB::Raw('SUM(message_count) AS count, SUM(`unread_count`) AS unreads'))
            ->where('to_uid', $uid)
            ->get();
    }

    /**
     * 获取消息列表数量
     *
     * @param int $uid
     * @param int $from_uid
     * @return int
     */
    public function countDialogs($uid)
    {
        return self::where('to_uid', $uid)
            ->count();
    }

    /**
     * 消息列表
     *
     * @param int $uid
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function getDialogs($uid, $start, $limit)
    {
        return self::where('to_uid', $uid)
            ->orderby('modified_time', 'desc')
            ->paginate($limit);
    }

    public function getDialogIds($uid)
    {
        return self::where('to_uid', $uid)
            ->pluck('dialog_id');
    }

    public function fetchDialogByDialogIds($dialogIds)
    {
        return self::whereIn('dialog_id', $dialogIds)
            ->get();
    }

    public function deleteDialog($dialogId)
    {
        return self::destory($dialogId);
    }

    public function batchDeleteDialog($dialogIds)
    {
        return self::destory($dialogIds);
    }

}