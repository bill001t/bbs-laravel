<?php

namespace App\Services\Api\message\ds\dao;

use App\Services\Api\message\ds\relation\MessageRelation;
use DB;

class WindidMessageRelationDao extends MessageRelation
{
    /**
     * 添加消息关系
     *
     * @param array $fields
     * @return int
     */
    public function addMessageRelation($fields)
    {
        return self::create($fields);
    }

    public function batchReadRelation($relationIds)
    {
        return self::whereIn('id', $relationIds)
            ->update(['is_read', 1]);
    }

    /**
     * 更新消息关系表
     *
     * @param int $dialogId
     * @param array $messageIds
     * @return int
     */
    public function readMessages($dialogId, $messageIds)
    {
        return self::where('dialog_id', $dialogId)
            ->whereIn('message_id', $messageIds)
            ->update(['is_read', 1]);
    }

    /**
     * 更新消息关系表
     *
     * @param int $dialogId
     * @return int
     */
    public function readDialogMessages($dialogId)
    {
        return self::where('dialog_id', $dialogId)
            ->update(['is_read', 1]);
    }

    public function countRelation($dialogId)
    {
        return self::where('dialog_id', $dialogId)
            ->count();
    }

    /**
     * 统计信息数量
     *
     * @param int $dialogId
     * @return array
     */
    public function countDialogMessages($dialogId)
    {
        return self::select(DB::Raw('COUNT(*) AS count,SUM(is_read) AS reads'))
            ->where('dialog_id', $dialogId)
            ->get();
    }

    public function countUnreadMessageByDialogIds($dialogIds)
    {
        return self::whereIn('dialog_id', $dialogIds)
            ->where('is_read', 0)
            ->count();
    }

    /**
     * 获取对话私信
     *
     * @param int $dialogId
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function getDialogMessages($dialogId, $limit, $start)
    {
        return self::where('dialog_id', $dialogId)
            ->orderby('message_id', 'DESC')
            ->paginate($limit);
    }

    /**
     * 根据message_id获取前面几条
     *
     * @param int $dialogId
     * @param int $messageId
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function getPreviousMessage($dialogId, $messageId, $num)
    {
        return self::select('message_id')
            ->where('dialog_id', $dialogId)
            ->where('message_id', '<', $messageId)
            ->orderby('created_time', 'DESC')
            ->paginate($num);
    }

    /**
     * 根据message_id获取后面几条
     *
     * @param int $dialogId
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function getNextMessage($dialogId, $messageId, $num)
    {
        return self::select('message_id')
            ->where('dialog_id', $dialogId)
            ->where('message_id', '>', $messageId)
            ->orderby('created_time', 'ASC')
            ->paginate($num);
    }

    /**
     * 根据$ids删除关系
     *
     * @param array $ids
     * @return bool
     */
    public function batchDeleteRelation($ids)
    {
        return self::destroy($ids);
    }

    public function getRelationsByMessageIds($messageIds)
    {
        return self::whereIn('message_id', $messageIds)
            ->get();
    }

    public function fetchRelationByMessageIds($messageIds, $issend = 0)
    {
        return self::whereIn('message_id', $messageIds)
            ->where('is_send', $issend)
            ->get();
    }

    /**
     * 根据messageId删除单个关系
     *
     * @param int $dialogId
     * @param int $messageId
     * @return bool
     */
    public function deleteRelation($dialogId, $messageId)
    {
        return self::where('dialog_id', $dialogId)
            ->where('message_id', $messageId)
            ->delete();
    }

    public function batchDeleteRelationByDialogIds($dialogIds)
    {
        return self::whereIn('dialog_id', $dialogIds)
            ->delete();
    }

    public function batchDeleteByDialogAndMessages($dialogId, $messgeIds)
    {
        return self::where('dialog_id', $dialogId)
            ->whereIn('message_id', $messgeIds)
            ->delete();
    }

}