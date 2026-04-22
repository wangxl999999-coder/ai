<?php

namespace app\model;

use think\Model;

/**
 * 用户模型
 */
class User extends Model
{
    // 表名
    protected $name = 'users';
    
    // 自动时间戳
    protected $autoWriteTimestamp = 'int';
    
    // 时间字段类型
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    
    // 获取器：头像
    public function getAvatarAttr($value)
    {
        if (empty($value)) {
            return get_default_avatar();
        }
        return $value;
    }
    
    /**
     * 关联提示词
     */
    public function prompts()
    {
        return $this->hasMany(Prompt::class, 'user_id', 'id');
    }
    
    /**
     * 关联工作流
     */
    public function workflows()
    {
        return $this->hasMany(Workflow::class, 'user_id', 'id');
    }
    
    /**
     * 关联评论
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }
    
    /**
     * 关联收藏
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id', 'id');
    }
    
    /**
     * 关联点赞
     */
    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id', 'id');
    }
    
    /**
     * 关联积分记录
     */
    public function pointsRecords()
    {
        return $this->hasMany(PointsRecord::class, 'user_id', 'id');
    }
    
    /**
     * 关联购买记录
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'user_id', 'id');
    }
    
    /**
     * 增加积分
     * @param int $points 积分数量
     * @param string $sourceType 来源类型
     * @param int $sourceId 来源ID
     * @param string $description 描述
     * @return bool
     */
    public function addPoints($points, $sourceType, $sourceId = 0, $description = '')
    {
        $this->points += $points;
        $this->save();
        
        // 记录积分变动
        PointsRecord::create([
            'user_id' => $this->id,
            'type' => 1,
            'points' => $points,
            'balance' => $this->points,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'description' => $description ?: '获得' . $points . '积分',
            'create_time' => time(),
        ]);
        
        return true;
    }
    
    /**
     * 扣除积分
     * @param int $points 积分数量
     * @param string $sourceType 来源类型
     * @param int $sourceId 来源ID
     * @param string $description 描述
     * @return bool
     */
    public function deductPoints($points, $sourceType, $sourceId = 0, $description = '')
    {
        if ($this->points < $points) {
            return false;
        }
        
        $this->points -= $points;
        $this->save();
        
        // 记录积分变动
        PointsRecord::create([
            'user_id' => $this->id,
            'type' => 2,
            'points' => $points,
            'balance' => $this->points,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'description' => $description ?: '消耗' . $points . '积分',
            'create_time' => time(),
        ]);
        
        return true;
    }
}
