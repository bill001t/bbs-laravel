<?php

namespace App\Core;

use Crypt;
/**
 * 字符、路径过滤等安全处理
 *
 * @author Qiong Wu <papa0924@gmail.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id: WindSecurity.php 3939 2013-05-29 06:22:57Z xiaoxia.xuxx $
 * @package utility
 */
class Security
{

    /**
     * 输出json到页面
     * 添加转义
     *
     * @param mixed $source
     * @param string $charset
     * @return string
     */
    public static function escapeEncodeJson($source)
    {
        return json_encode(is_string($source) ? self::escapeHTML($source) : self::escapeArrayHTML($source));
    }

    /**
     * 转义输出字符串
     *
     * @param string $str 被转义的字符串
     * @return string
     */
    public static function escapeHTML($str)
    {
        if (!is_string($str)) return $str;
        return htmlspecialchars($str, ENT_QUOTES);
    }

    /**
     * 转义字符串
     *
     * @param array $array 被转移的数组
     * @return array
     */
    public static function escapeArrayHTML($array)
    {
        if (!is_array($array)) return self::escapeHTML($array);
        $_tmp = array();
        foreach ($array as $key => $value) {
            is_string($key) && $key = self::escapeHTML($key);
            $_tmp[$key] = is_array($value) ? self::escapeArrayHTML($value) : self::escapeHTML($value);
        }
        return $_tmp;
    }

    /**
     * 字符串加密
     *
     * @param string $str 需要加密的字符串
     * @param string $key 密钥
     * @return string 加密后的结果
     */
    public static function encrypt($str, $key, $iv = '')
    {
        return Crypt::encrypt($str);
    }

    /**
     * 解密字符串
     *
     * @param string $str 解密的字符串
     * @param string $key 密钥
     * @return string 解密后的结果
     */
    public static function decrypt($str, $key, $iv = '')
    {
        return Crypt::decrypt($str);
    }

    /**
     * 创建token令牌串
     * 创建token令牌串,用于避免表单重复提交等.
     * 使用当前的sessionID以及当前时间戳,生成唯一一串令牌串,并返回.
     *
     * @deprecated
     *
     * @return string
     */
    public static function createToken()
    {
        return self::generateGUID();
    }

    /**
     * 获取唯一标识符串,标识符串的长度为16个字节,128位.
     * 根据当前时间与sessionID,混合生成一个唯一的串.
     *
     * @return string GUID串,16个字节
     */
    public static function generateGUID()
    {
        return substr(md5(Utility::generateRandStr(8) . microtime()), -16);
    }

    /**
     * 路径检查转义
     *
     * @param string $fileName 被检查的路径
     * @param boolean $ifCheck 是否需要检查文件名，默认为false
     * @return string
     */
    public static function escapePath($filePath, $ifCheck = false)
    {
        $_tmp = array("'" => '', '#' => '', '=' => '', '`' => '', '$' => '', '%' => '', '&' => '', ';' => '');
        $_tmp['://'] = $_tmp["\0"] = '';
        $ifCheck && $_tmp['..'] = '';
        if (strtr($filePath, $_tmp) == $filePath) return preg_replace('/[\/\\\]{1,}/i', '/', $filePath);
        throw new CommonErrorException('[utility.WindSecurity.escapePath] file path is illegal');
    }
}