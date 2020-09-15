<?php

namespace App\Core;

class EducationHelper
{

    /**
     * 返回教育时间
     * 倒序返回-倒退100年
     *
     * @return array
     */
    public static function getEducationYear()
    {
        $tyear = Tool::time2str(Tool::getTime(), 'Y');
        return range($tyear, $tyear - 100, -1);
    }

    /**
     * 检查教育时间是否非法
     *
     * @param int $year
     * @return int
     */
    public static function checkEducationYear($year)
    {
        $endYear = Tool::time2str(Tool::getTime(), 'Y');
        if ($year > $endYear) {
            $year = $endYear;
        } elseif ($year < ($endYear - 100)) {
            $year = $endYear - 100;
        }
        return $year;
    }

    /**
     * 获得学历
     *
     * @param string $select 需要返回的数据key
     * @return array
     */
    public static function getDegrees($selected = '')
    {
        $degrees = array(
            '8' => '博士后',
            '7' => '博士',
            '6' => '硕士',
            '5' => '大学本科',
            '4' => '大学专科',
            '3' => '高中',
            '2' => '初中',
            '1' => '小学',
        );
        return $selected ? $degrees[$selected] : $degrees;
    }

    /**
     * 检查是否符合
     *
     * @param string $degree
     * @return boolean
     */
    public static function checkDegree($degree)
    {
        return array_key_exists($degree, self::getDegrees());
    }
}