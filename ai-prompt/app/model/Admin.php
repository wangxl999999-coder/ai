<?php

namespace app\model;

use think\Model;

/**
 * 管理员模型
 */
class Admin extends Model
{
    // 表名
    protected $name = 'admins';
    
    // 自动时间戳
    protected $autoWriteTimestamp = 'int';
    
    // 时间字段类型
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    
    // 获取器：头像
    public function getAvatarAttr($value)
    {
        if (empty($value)) {
            return get_default_admin_avatar();
        }
        return $value;
    }
}
