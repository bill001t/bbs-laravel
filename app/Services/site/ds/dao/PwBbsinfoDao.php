<?php

namespace App\Services\site\ds\dao;

use App\Services\site\ds\relation\bbsInfo;

class PwBbsinfoDao extends bbsInfo
{

    public function get($id)
    {
        return self::find($id);
    }

    public function _update($id, $fields, $increaseFields = array())
    {
        $sql = self::where('id', $id);

        foreach($increaseFields as $k => $v){
            $sql->increment($k, $v);
        }

        return $sql->update($fields);
    }
}