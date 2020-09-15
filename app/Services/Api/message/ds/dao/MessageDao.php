<?php

namespace App\Services\Api\message\ds\dao;

use App\Services\Api\message\ds\relation\Message;

class WindidMessageDao extends Message
{

    /**
     * 获取单条消息
     *
     * @param int $id
     * @return array
     */
    public function getMessage($id)
    {
        return self::find($id);
    }

    public function fetchMessage($ids)
    {
        return self::whereIn('message_id', $ids)
            ->get();
    }

    /**
     * 添加单条消息
     *
     * @param array $fields
     * @return bool
     */
    public function addMessage($fields)
    {
        return self::create($fields);
    }

    /**
     * 删除单条消息
     *
     * @param int $id
     * @return bool
     */
    public function deleteMessage($id)
    {
        self::destroy($id);
    }

    /**
     * 删除多条消息
     *
     * @param array $ids
     * @return bool
     */
    public function deleteMessages($ids)
    {
        return self::destroy($ids);
    }

    /**
     * 根据Ids获取消息
     *
     * @param array $ids
     * @return array
     */
    public function getMessagesByIds($ids)
    {
        return self::whereIn('message_id', $ids)
            ->get();
    }

    /**
     * 搜索消息数量
     *
     * @param int $from_uid
     * @param int $starttime
     * @param int $endtime
     * @return array
     */
    public function countMessage($data)
    {
        $sql = self::whereRaw('1 = 1');

        $sql = $this->_buildCondition($sql, $data);

        return $sql->count();
    }

    /**
     * 搜索消息
     */
    public function searchMessage($data, $start, $limit)
    {
        $sql = self::whereRaw('1 = 1');

        $sql = $this->_buildCondition($sql, $data);

        return $sql->orderby('created_time', 'DESC')
            ->paginate($limit);
    }

    private function _buildCondition($sql, $data)
    {
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'fromuid':
                    $sql = $sql->where('from_uid', $value);
                    break;
                case 'touid':
                    $sql = $sql->where('to_uid', $value);
                    break;
                case 'keyword':
                    $sql = $sql->where('content', 'like', '%' . $value . '%');
                    break;
                case 'starttime':
                    $sql = $sql->where('created_time', ' >=', $value);
                    break;
                case 'endtime':
                    $sql = $sql->where('created_time', ' <=', $value);
                    break;
            }
        }
        return $sql;
    }
}