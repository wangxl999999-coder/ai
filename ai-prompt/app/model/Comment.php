<?php

namespace app\model;

use think\Model;

/**
 * 评论模型
 */
class Comment extends Model
{
    // 表名
    protected $name = 'comments';
    
    // 自动时间戳
    protected $autoWriteTimestamp = 'int';
    
    // 时间字段类型
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    
    /**
     * 关联用户
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    /**
     * 关联回复用户
     */
    public function replyUser()
    {
        return $this->belongsTo(User::class, 'reply_user_id', 'id');
    }
    
    /**
     * 关联子评论
     */
    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id')
            ->where('status', 1)
            ->order('create_time', 'asc');
    }
    
    /**
     * 检查用户是否已点赞
     * @param int $userId 用户ID
     * @return bool
     */
    public function isLiked($userId)
    {
        return Like::where('user_id', $userId)
            ->where('target_type', 'comment')
            ->where('target_id', $this->id)
            ->find() ? true : false;
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
}
