<?php

namespace App\Core;

class Utility
{

    /**
     * 生成密码
     *
     * @param string $password 源密码
     * @param string $salt
     * @return string
     */
    public static function buildPassword($password, $salt)
    {
        return md5(md5($password) . $salt);
    }

    /**
     * 安全问题加密
     *
     * @param string $question
     * @param string $answer
     * @return bool
     */
    public static function buildQuestion($question, $answer)
    {
        return substr(md5($question . $answer), 8, 8);
    }

    /**
     * 获得随机数字符串
     *
     * @param int $length
     *        	随机数的长度
     * @return string 随机获得的字串
     */
    public static function generateRandStr($length) {
        $mt_string = 'AzBy0CxDwEv1FuGtHs2IrJqK3pLoM4nNmOlP5kQjRi6ShTgU7fVeW8dXcY9bZa';
        $randstr = '';
        for ($i = 0; $i < $length; $i++) {
            $randstr .= $mt_string[mt_rand(0, 61)];
        }
        return $randstr;
    }

    /**
     * 执行简单的条件表达式
     *
     * 只能执行==、！=、<、>、<=、>=简单的比较
     *
     * @param string $v1
     *        	左边的操作数
     * @param string $v2
     *        	右边的操作数
     * @param string $operator
     *        	操作符号
     * @return boolean
     */
    public static function evalExpression($v1, $v2, $operator) {
        switch ($operator) {
            case '==':
                return $v1 == $v2;
            case '!=':
                return $v1 != $v2;
            case '<':
                return $v1 < $v2;
            case '>':
                return $v1 > $v2;
            case '<=':
                return $v1 <= $v2;
            case '>=':
                return $v1 >= $v2;
            default:
                return false;
        }
        return false;
    }

    public static function resolveExpression($expression) {
        $operators = array('==', '!=', '<', '>', '<=', '>=');
        $operatorsReplace = array('#==#', '#!=#', '#<#', '#>#', '#<=#', '#>=#');
        list($p, $o, $v2) = explode('#', str_replace($operators, $operatorsReplace, $expression));
        if (strpos($p, ":") !== false)
            list($_namespace, $p) = explode(':', trim($p, ':'));
        else
            $_namespace = '';
        return array($_namespace, $p, $o, $v2);
    }

}