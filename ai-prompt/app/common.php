<?php
// 应用公共函数文件

/**
 * 生成随机验证码
 * @param int $length 验证码长度
 * @return string
 */
function generate_code($length = 6)
{
    $chars = '0123456789';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $code;
}

/**
 * 验证手机号格式
 * @param string $phone
 * @return bool
 */
function is_phone($phone)
{
    return preg_match('/^1[3-9]\d{9}$/', $phone);
}

/**
 * 生成用户默认昵称
 * @return string
 */
function generate_nickname()
{
    $prefix = ['用户', '达人', '创作者', '设计师', '工程师', '艺术家'];
    $suffix = substr(md5(uniqid(mt_rand(), true)), 0, 6);
    return $prefix[array_rand($prefix)] . $suffix;
}

/**
 * 获取用户默认头像
 * @return string
 */
function get_default_avatar()
{
    return '/static/images/default_avatar.png';
}

/**
 * 获取管理员默认头像
 * @return string
 */
function get_default_admin_avatar()
{
    return '/static/images/default_admin_avatar.png';
}

/**
 * 密码加密
 * @param string $password
 * @return string
 */
function password_encrypt($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * 获取客户端真实IP
 * @return string
 */
function get_real_ip()
{
    $ip = '';
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '127.0.0.1';
}

/**
 * 格式化时间
 * @param int $time
 * @return string
 */
function format_time($time)
{
    $now = time();
    $diff = $now - $time;
    
    if ($diff < 60) {
        return '刚刚';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . '分钟前';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . '小时前';
    } elseif ($diff < 2592000) {
        return floor($diff / 86400) . '天前';
    } else {
        return date('Y-m-d', $time);
    }
}

/**
 * 截断文本
 * @param string $text
 * @param int $length
 * @param string $suffix
 * @return string
 */
function truncate_text($text, $length = 100, $suffix = '...')
{
    $text = strip_tags($text);
    if (mb_strlen($text, 'utf-8') <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length, 'utf-8') . $suffix;
}
