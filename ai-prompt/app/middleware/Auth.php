<?php

namespace app\middleware;

use think\Response;

class Auth
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        // 检查用户是否登录
        $userId = session('user_id');
        
        if (!$userId) {
            // 如果是AJAX请求，返回JSON
            if ($request->isAjax()) {
                return json(['code' => 401, 'msg' => '请先登录', 'data' => []]);
            }
            
            // 否则跳转登录页面
            return redirect(url('/user/login'));
        }
        
        // 将用户ID注入到请求对象中
        $request->userId = $userId;
        
        return $next($request);
    }
}
