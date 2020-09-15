<?php

namespace App\Core;

Trait BaseDaoTrait
{
    public function sqlSingleBit($array)
    {
        if (!is_array($array)) return '';

        $result = array();
        foreach ($array as $key => $val) {
            if (!$val || !is_array($val)) continue;

            foreach ($val as $bit => $v) {
                $key = $v ? ($key | 1<< ($bit - 1)) : ($key & ~ (1<< ($bit - 1)));
            }
            $result[$key] = $key;
        }
        return $result ? implode(',', $result) : '';
    }
}