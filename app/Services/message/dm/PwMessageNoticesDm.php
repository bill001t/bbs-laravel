<?php

namespace App\Services\message\dm;

use App\Core\BaseDm;

class PwMessageNoticesDm extends BaseDm
{

    public $id;

    public function __construct($id = 0)
    {
        $id = intval($id);
        $id > 0 && $this->id = $id;
    }

    public function setId($id)
    {
        $id = intval($id);
        $id > 0 && $this->id = $id;
    }

    public function setToUid($uid)
    {
        $uid = intval($uid);
        $this->_data['uid'] = $uid;
        return $this;
    }

    public function setType($type)
    {
        $type = intval($type);
        $this->_data['typeid'] = $type;
        return $this;
    }

    public function setIgnore($ignore)
    {
        $ignore = intval($ignore);
        $this->_data['is_ignore'] = $ignore ? 1 : 0;
        return $this;
    }

    public function setRead($read)
    {
        $read = intval($read);
        $this->_data['is_read'] = $read ? 1 : 0;
        return $this;
    }

    public function setParam($param)
    {
        $param = intval($param);
        $this->_data['param'] = $param;
        return $this;
    }

    public function setExtendParams($params)
    {
        $this->_data['extend_params'] = $params;
        return $this;
    }

    public function setTitle($title)
    {
        $this->_data['title'] = $title;
        return $this;
    }

    public function setModifiedTime($modified_time)
    {
        $this->_data['modified_time'] = $modified_time;
        return $this;
    }

    protected function _beforeAdd()
    {
        $this->_data['created_time'] = $this->_data['modified_time'] = Tool::getTime();
        /*
        if (($result = $this->checkContent()) !== true) {
            return $result;
        }
        */
        $this->_checkDataSerialize();
        return true;
    }

    protected function _beforeUpdate()
    {
        // $this->_data['modified_time'] = Tool::getTime();
        $this->_checkDataSerialize();
        return true;
    }

    protected function _checkDataSerialize()
    {
        foreach (array('extend_params') as $key => $value) {
            isset($this->_data[$value]) && ($this->_data[$value] = serialize($this->_data[$value]));
        }
    }
}

?>