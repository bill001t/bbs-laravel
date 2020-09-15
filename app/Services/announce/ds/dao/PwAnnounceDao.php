<?php

namespace App\Services\announce\ds\dao;

use App\Services\announce\ds\relation\Announce;

class PwAnnounceDao extends Announce
{


    /**
     * 添加一条公告信息
     *
     * @param array $fields
     * @return int
     */
    public function addAnnounce($fields)
    {
        return self::create($fields);
    }

    /**
     * 删除一条公告信息
     *
     * @param int $aid
     * @return boolean
     */
    public function deleteAnnounce($aid)
    {
        return self::destroy($aid);
    }

    /**
     * 批量删除公告信息
     *
     * @param array $aids
     * @return boolean
     */
    public function batchDeleteAnnounce($aids)
    {
        return self::destroy($aids);
    }

    /**
     * 更新一条公告信息
     * @param int $aid
     * @param array $fields
     * @return boolean
     */
    public function updateAnnounce($aid, $fields)
    {
        return self::where('aid', $aid)
            ->update($fields);
    }

    /**
     * 获取公告信息
     *
     * @param $offset
     * @param $limit
     * @return array
     */
    public function getAnnounceOrderByVieworder($limit, $offset)
    {
        return self::all()
            ->orderby('vieworder', 'ASC')
            ->paginate($limit);
    }

    /**
     * 通过时间获取公告信息
     * 业务为获取正在发布中的公告信息
     *
     * @param $time
     * @param $offset
     * @param $limit
     * @return array
     */
    public function getAnnounceByTimeOrderByVieworder($time, $limit, $offset)
    {
        return self::where('start_date', '<', $time)
            ->where('end_date', '>', $time)
            ->orderby('vieworder', 'ASC')
            ->paginate($limit);
    }


    /**
     * 获取公告数
     *
     * @return int
     */
    public function countAnnounce()
    {
        return self::count();
    }

    /**
     * 获取某一时间内的公告数
     * 业务为获取发布中公告的数量值
     *
     * @param int $time
     * @return int
     */
    public function countAnnounceByTime($time)
    {
        return self::where('start_date', '<', $time)
            ->where('end_date', '>', $time)
            ->count();
    }

    /**
     * 获取一条公告信息
     *
     * @param int $aid
     * @return array
     */
    public function getAnnounce($aid)
    {
        return self::find($aid);
    }

}