<?php

namespace app\model;

use think\Model;

/**
 * 积分记录模型
 */
class PointsRecord extends Model
{
    // 表名
    protected $name = 'points_records';
    
    // 自动时间戳
    protected $autoWriteTimestamp = 'int';
    
    // 时间字段类型
    protected $createTime = 'create_time';
    
    // 类型常量
    const TYPE_INCOME = 1; // 收入
    const TYPE_EXPENSE = 2; // 支出
    
    /**
     * 关联用户
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    /**
     * 获取器：类型文本
     */
    public function getTypeTextAttr($value, $data)
    {
        $types = [
            self::TYPE_INCOME => '收入',
            self::TYPE_EXPENSE => '支出',
        ];
        return $types[$data['type']] ?? '未知';
    }
}
