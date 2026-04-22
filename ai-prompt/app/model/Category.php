<?php

namespace app\model;

use think\Model;

/**
 * 分类模型
 */
class Category extends Model
{
    // 表名
    protected $name = 'categories';
    
    // 自动时间戳
    protected $autoWriteTimestamp = 'int';
    
    // 时间字段类型
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    
    // 类型常量
    const TYPE_PROMPT = 1; // 提示词
    const TYPE_WORKFLOW = 2; // 工作流
    
    /**
     * 获取提示词分类列表
     * @return array
     */
    public static function getPromptCategories()
    {
        return self::where('type', self::TYPE_PROMPT)
            ->where('status', 1)
            ->order('sort', 'asc')
            ->select()
            ->toArray();
    }
    
    /**
     * 获取工作流分类列表
     * @return array
     */
    public static function getWorkflowCategories()
    {
        return self::where('type', self::TYPE_WORKFLOW)
            ->where('status', 1)
            ->order('sort', 'asc')
            ->select()
            ->toArray();
    }
}
