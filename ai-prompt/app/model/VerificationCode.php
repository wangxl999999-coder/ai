<?php

namespace app\model;

use think\Model;

/**
 * 验证码模型
 */
class VerificationCode extends Model
{
    // 表名
    protected $name = 'verification_codes';
    
    // 自动时间戳
    protected $autoWriteTimestamp = 'int';
    
    // 时间字段类型
    protected $createTime = 'create_time';
    
    // 类型常量
    const TYPE_REGISTER = 'register'; // 注册
    const TYPE_RESET_PASSWORD = 'reset_password'; // 重置密码
    
    /**
     * 检查验证码是否有效
     * @param string $phone 手机号
     * @param string $code 验证码
     * @param string $type 类型
     * @return bool
     */
    public static function check($phone, $code, $type)
    {
        $record = self::where('phone', $phone)
            ->where('code', $code)
            ->where('type', $type)
            ->where('used', 0)
            ->where('expire_time', '>', time())
            ->order('id', 'desc')
            ->find();
        
        if ($record) {
            $record->used = 1;
            $record->save();
            return true;
        }
        
        return false;
    }
    
    /**
     * 生成并保存验证码
     * @param string $phone 手机号
     * @param string $type 类型
     * @param int $expireSeconds 有效期（秒）
     * @return string 验证码
     */
    public static function generate($phone, $type, $expireSeconds = 300)
    {
        $code = generate_code(6);
        
        self::create([
            'phone' => $phone,
            'code' => $code,
            'type' => $type,
            'used' => 0,
            'expire_time' => time() + $expireSeconds,
            'create_time' => time(),
        ]);
        
        return $code;
    }
}
