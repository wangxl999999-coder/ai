<?php
// 中间件配置
return [
    // 别名配置
    'alias' => [
        'auth' => app\middleware\Auth::class,
        'admin_auth' => app\middleware\AdminAuth::class,
    ],
    
    // 优先级设置，此数组中的中间件会按照数组中的顺序优先执行
    'priority' => [
        \think\middleware\SessionInit::class,
    ],
];
