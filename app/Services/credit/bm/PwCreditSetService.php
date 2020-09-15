<?php

namespace App\Services\credit\bm;

use App\Services\config\bs\PwConfig;
use App\Services\user\ds\dao\PwUserDataDao;
use App\Core\ErrorBag;
use App\Core\config\PwConfigSet;

class PwCreditSetService
{

    /**
     * 获得设置的积分选项
     *
     * @return array
     */
    public function getCredit()
    {
        $name = 'credit:credits';
        $key = '';
        if (strpos($name, ':') !== false) {
            list($namespace, $key) = explode(':', $name);
        } else {
            $namespace = $name;
        }
        $config = $this->_getConfigDs()->getValues($namespace);
        return $key ? $config[$key] : $config;
    }

    /**
     * 设置用户积分
     *
     * @param array $credit 积分配置信息<array('1' => array('name'=>?,'unit'=>?,'descrip'=>?), '2' => ?, ...)>
     * @param array $new 新增加的积分
     * @return boolean
     */
    public function setCredits($credits, $newCredit = array())
    {
        is_array($credits) || $credits = array();
        if ($newCredit) {
            $keys = array_keys($credits);
            $maxKey = intval(max($keys));
            $range = range(1, $maxKey + count($newCredit));
            $freeKeys = array_diff($range, $keys);
            asort($freeKeys);

            foreach ($newCredit as $key => $value) {
                if (!$value['name']) continue;
                $_key = array_shift($freeKeys);
                $credits[$_key] = $value;
            }
        }

        $this->setLocalCredits($credits);
        return true;
    }

    /**
     * 设置本地积分配置
     *
     * @param array $credits
     * @return bool
     */
    public function setLocalCredits($credits)
    {
        $struct = $this->_getDs()->getCreditStruct();
        foreach ($credits as $key => $value) {
            if (!in_array('credit' . $key, $struct)) {
                $this->_getDs()->alterAddCredit($key);
            }
        }
        foreach ($struct as $key => $value) {
            $_key = substr($value, 6);
            if (!isset($credits[$_key])) {
                if ($_key < 9) {
                    $this->_getDs()->clearCredit($_key);
                } else {
                    $this->_getDs()->alterDropCredit($_key);
                }
            }
        }
        $config = new PwConfigSet('credit');
        $config->set('credits', $credits)->flush();
        return true;
    }

    /**
     * 删除积分
     *
     * @param int $creditId 积分ID
     * @return ErrorBag|boolean
     */
    public function deleteCredit($creditId)
    {
        if ($creditId < 0) {
            return new ErrorBag("User:deleteCredit.illegal.creditId");
        }

        $creditConfig = Core::C()->getConfigByName('credit', 'credits');
        $credits = unserialize($creditConfig['value']);
        unset($credits[$creditId]);

        $this->setLocalCredits($credits);
        return true;
    }

    private function _getDs()
    {
        return app(PwUserDataDao::class);
    }

    private function _getConfigDs(){
        return app(PwConfig::class);
    }

}