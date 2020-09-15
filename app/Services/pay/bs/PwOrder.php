<?php

namespace App\Services\pay\bs;

use App\Services\pay\dm\PwOrderDm;
use App\Services\pay\ds\dao\PwOrderDao;

class PwOrder
{

    /**
     * 获取一个订单
     *
     * @param int $id 订单id
     * return array
     */
    public function getOrder($id)
    {
        if (empty($id)) return array();
        return $this->_getDao()->getOrder($id);
    }

    /**
     * 获取一个订单
     *
     * @param string $orderno 订单号
     * return array
     */
    public function getOrderByOrderNo($orderno)
    {
        if (empty($orderno)) return array();
        return $this->_getDao()->getOrderByOrderNo($orderno);
    }

    public function countByUidAndType($uid, $type)
    {
        if (empty($uid)) return 0;
        return $this->_getDao()->countByUidAndType($uid, $type);
    }

    /**
     * 获取用户某一类型的订单
     *
     * @param int $uid
     * @param int $type
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getOrderByUidAndType($uid, $type, $limit = 20, $offset = 0)
    {
        if (empty($uid)) return array();
        return $this->_getDao()->getOrderByUidAndType($uid, $type, $limit, $offset);
    }

    /**
     * 增加一个订单
     *
     * @param object $dm 订单数据模型
     * return mixed
     */
    public function addOrder(PwOrderDm $dm)
    {
        if (($result = $dm->beforeAdd()) !== true) {
            return $result;
        }
        return $this->_getDao()->addOrder($dm->getData());
    }

    /**
     * 更新一个订单
     *
     * @param object $dm 订单数据模型
     * return mixed
     */
    public function updateOrder(PwOrderDm $dm)
    {
        if (($result = $dm->beforeUpdate()) !== true) {
            return $result;
        }
        return $this->_getDao()->updateOrder($dm->id, $dm->getData());
    }

    protected function _getDao()
    {
        return app(PwOrderDao::class);
    }
}