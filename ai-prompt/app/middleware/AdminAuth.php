<?php

namespace app\middleware;

use think\Response;

class AdminAuth
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
        // 检查管理员是否登录
        $adminId = session('admin_id');
        
        if (!$adminId) {
            // 如果是AJAX请求，返回JSON
            if ($request->isAjax()) {
                return json(['code' => 401, 'msg' => '请先登录', 'data' => []]);
            }
            
            // 否则跳转登录页面
            return redirect(url('/admin/login'));
        }
        
        // 将管理员ID注入到请求对象中
        $request->adminId = $adminId;
        
        return $next($request);
    }
}
