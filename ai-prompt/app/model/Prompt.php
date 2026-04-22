<?php

namespace app\model;

use think\Model;

/**
 * 提示词模型
 */
class Prompt extends Model
{
    // 表名
    protected $name = 'prompts';
    
    // 自动时间戳
    protected $autoWriteTimestamp = 'int';
    
    // 时间字段类型
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    
    // 状态常量
    const STATUS_PENDING = 1; // 待审核
    const STATUS_PASSED = 2; // 已通过
    const STATUS_REJECTED = 3; // 已拒绝
    const STATUS_DISABLED = 0; // 禁用
    
    /**
     * 关联用户
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    /**
     * 关联分类
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    
    /**
     * 关联评论
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'target_id', 'id')
            ->where('target_type', 'prompt')
            ->where('status', 1)
            ->order('create_time', 'desc');
    }
    
    /**
     * 检查用户是否已购买
     * @param int $userId 用户ID
     * @return bool
     */
    public function isPurchased($userId)
    {
        if ($this->user_id == $userId) {
            return true;
        }
        
        return Purchase::where('user_id', $userId)
            ->where('target_type', 'prompt')
            ->where('target_id', $this->id)
            ->find() ? true : false;
    }
    
    /**
     * 检查用户是否已收藏
     * @param int $userId 用户ID
     * @return bool
     */
    public function isFavorited($userId)
    {
        return Favorite::where('user_id', $userId)
            ->where('target_type', 'prompt')
            ->where('target_id', $this->id)
            ->find() ? true : false;
    }
    
    /**
     * 检查用户是否已点赞
     * @param int $userId 用户ID
     * @return bool
     */
    public function isLiked($userId)
    {
        return Like::where('user_id', $userId)
            ->where('target_type', 'prompt')
            ->where('target_id', $this->id)
            ->find() ? true : false;
    }
    
    /**
     * 增加查看次数
     */
    public function incrementViewCount()
    {
        $this->view_count += 1;
        return $this->save();
    }
    
    /**
     * 增加点赞数
     */
    public function incrementLikeCount()
    {
        $this->like_count += 1;
        return $this->save();
    }
    
    /**
     * 减少点赞数
     */
    public function decrementLikeCount()
    {
        if ($this->like_count > 0) {
            $this->like_count -= 1;
            return $this->save();
        }
        return true;
    }
    
    /**
     * 增加收藏数
     */
    public function incrementFavoriteCount()
    {
        $this->favorite_count += 1;
        return $this->save();
    }
    
    /**
     * 减少收藏数
     */
    public function decrementFavoriteCount()
    {
        if ($this->favorite_count > 0) {
            $this->favorite_count -= 1;
            return $this->save();
        }
        return true;
    }
    
    /**
     * 增加评论数
     */
    public function incrementCommentCount()
    {
        $this->comment_count += 1;
        return $this->save();
    }
    
    /**
     * 减少评论数
     */
    public function decrementCommentCount()
    {
        if ($this->comment_count > 0) {
            $this->comment_count -= 1;
            return $this->save();
        }
        return true;
    }
}
