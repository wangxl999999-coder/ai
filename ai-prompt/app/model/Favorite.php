<?php

namespace app\model;

use think\Model;

/**
 * 收藏模型
 */
class Favorite extends Model
{
    // 表名
    protected $name = 'favorites';
    
    // 自动时间戳
    protected $autoWriteTimestamp = 'int';
    
    // 时间字段类型
    protected $createTime = 'create_time';
    
    /**
     * 关联用户
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    /**
     * 关联目标（多态关联）
     */
    public function target()
    {
        switch ($this->target_type) {
            case 'prompt':
                return $this->belongsTo(Prompt::class, 'target_id', 'id');
            case 'workflow':
                return $this->belongsTo(Workflow::class, 'target_id', 'id');
            default:
                return null;
        }
    }
}
