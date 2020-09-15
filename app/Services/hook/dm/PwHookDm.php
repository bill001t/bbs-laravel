<?php

namespace App\Services\hook\dm;

use App\Core\BaseDm;

class PwHookDm extends BaseDm
{
    private $id;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setAppId($value)
    {
        $this->_data['app_id'] = $value;
    }

    public function setAppName($value)
    {
        $this->_data['app_name'] = $value;
    }

    public function setDocument($value)
    {
        $this->_data['document'] = $value;
    }

    public function setName($value)
    {
        $this->_data['name'] = $value;
    }

    public function setModifiedTime($value)
    {
        $this->_data['modified_time'] = $value;
    }

    public function setCreatedTime($value)
    {
        $this->_data['created_time'] = $value;
    }

    /* (non-PHPdoc)
     * @see BaseDm::_beforeAdd()
     */
    protected function _beforeAdd()
    {
        if (empty($this->_data['name'])) return array('HOOK:verify.required', array('{{error}}' => 'hook name'));
        return true;
    }

    /* (non-PHPdoc)
     * @see BaseDm::_beforeUpdate()
     */
    protected function _beforeUpdate()
    {
        if (empty($this->_data['name'])) return array('HOOK:verify.required', array('{{error}}' => 'hook name'));
        return true;
    }
}

?>