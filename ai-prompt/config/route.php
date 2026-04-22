<?php
// 路由配置
return [
    // URL分隔符
    'pathinfo_depr' => '/',
    
    // URL伪静态后缀
    'url_html_suffix' => 'html',
    
    // URL普通方式参数 用于自动生成
    'url_common_param' => false,
    
    // 是否开启路由延迟解析
    'url_lazy_route' => false,
    
    // 是否强制使用路由
    'url_route_must' => false,
    
    // 合并路由规则
    'route_rule_merge' => false,
    
    // 路由是否完全匹配
    'route_complete_match' => false,
    
    // 使用注解路由
    'route_annotation' => false,
    
    // 域名根，如thinkphp.cn
    'url_domain_root' => '',
    
    // 是否自动转换URL中的控制器和操作名
    'url_convert' => true,
    
    // 默认的访问控制器层
    'url_controller_layer' => 'controller',
    
    // 控制器和操作名的自动转换，设置为false表示不转换
    'controller_suffix' => false,
    
    // 默认的访问控制器层
    'controller_layer' => 'controller',
    
    // 空控制器名
    'empty_controller' => 'Error',
    
    // 是否使用控制器后缀
    'controller_suffix' => false,
    
    // 默认的验证类
    'default_validate' => '',
    
    // 默认的跳转地址
    'default_redirect' => '/',
    
    // 应用调度
    'dispatch' => [
        'success' => [
            // 跳转等待时间
            'wait' => 3,
            // 跳转页面路径
            'path' => 'dispatch_success',
        ],
        'error' => [
            // 跳转等待时间
            'wait' => 3,
            // 跳转页面路径
            'path' => 'dispatch_error',
        ],
    ],
];
