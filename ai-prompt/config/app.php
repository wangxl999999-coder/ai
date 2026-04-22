<?php
// 应用配置
return [
    // 默认时区
    'default_timezone' => 'Asia/Shanghai',
    
    // 应用映射（自动多应用模式有效）
    'app_map' => [],
    
    // 域名绑定（自动多应用模式有效）
    'domain_bind' => [],
    
    // 禁止URL访问的应用列表（自动多应用模式有效）
    'deny_app_list' => [],
    
    // 异常页面的模板文件
    'exception_tmpl' => app()->getThinkPath() . 'tpl/think_exception.tpl',
    
    // 错误显示信息,非调试模式有效
    'error_message' => '页面错误！请稍后再试～',
    
    // 显示错误信息
    'show_error_msg' => true,
    
    // 默认分页数量
    'list_rows' => 10,
    
    // 用户默认积分（注册赠送）
    'default_points' => 10,
    
    // 发布提示词积分奖励
    'publish_prompt_points' => 2,
    
    // 发布工作流积分奖励
    'publish_workflow_points' => 3,
    
    // 查看提示词积分费用
    'view_prompt_points' => 1,
    
    // 查看工作流积分费用
    'view_workflow_points' => 1,
    
    // 验证码有效期（秒）
    'code_expire_time' => 300,
];
