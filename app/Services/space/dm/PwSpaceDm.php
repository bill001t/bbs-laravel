<?php

namespace App\Services\space\dm;

use App\Core\BaseDm;
use App\Core\ErrorBag;
use App\Core\Tool;

class PwSpaceDm extends BaseDm
{

    public $uid;

    public function __construct($uid = null)
    {
        if (isset($uid)) $this->uid = (int)$uid;
    }

    public function setSpaceName($name)
    {
        $this->_data['space_name'] = Tool::substrs($name, 20, 0, false);
        return $this;
    }

    public function setSpaceDescrip($descrip)
    {
        $this->_data['space_descrip'] = Tool::substrs($descrip, 250, 0, false);
        return $this;
    }

    public function setSpaceDomain($domain)
    {
        $this->_data['space_domain'] = Tool::substrs($domain, 20, 0, false);
        return $this;
    }

    public function setSpaceStyle($style)
    {
        $this->_data['space_style'] = $style;
        return $this;
    }

    public function setBackImage($image, $repeat, $fixed, $align)
    {
        //$array = array('image'=>$image, 'repeat'=>$repeat, 'fixed'=>$fixed, 'align'=>$align);
        $array = array($image, $repeat, $fixed, $align);
        $this->_data['back_image'] = serialize($array);
        return $this;
    }

    public function setVisitCount($number)
    {
        $this->_data['visit_count'] = (int)$number;
        return $this;
    }

    public function setVisitors($visitors)
    {
        $visitors = is_array($visitors) ? $visitors : array();
        $this->_data['visitors'] = serialize($visitors);
        return $this;
    }

    public function setTovisitors($visitors)
    {
        $visitors = is_array($visitors) ? $visitors : array();
        $this->_data['tovisitors'] = serialize($visitors);
        return $this;
    }

    public function setSpacePrivacy($privacy)
    {
        $this->_data['space_privacy'] = intval($privacy);
        return $this;
    }

    protected function _beforeAdd()
    {
        if ($this->uid < 1) return new ErrorBag('SPACE:uid.empty');
        //if (empty($this->_data['space_name'])) return new ErrorBag('SPACE:spacename.empty');
        return true;
    }

    protected function _beforeUpdate()
    {
        if ($this->uid < 1) return new ErrorBag('SPACE:uid.empty');
        return true;
    }

}

?>