<?php

namespace app\admin\controller;

use app\BaseController;
use app\model\Admin;
use app\model\User;
use app\model\Prompt;
use app\model\Workflow;
use app\model\Category;
use app\model\Comment;
use think\facade\Session;
use think\facade\View;

/**
 * 管理后台控制器
 */
class AdminController extends BaseController
{
    // 中间件
    protected $middleware = [
        'admin_auth' => ['except' => ['login', 'doLogin']],
    ];
    
    /**
     * 管理员登录页面
     */
    public function login()
    {
        if (Session::has('admin_id')) {
            return redirect(url('/admin'));
        }
        
        return View::fetch('admin/login');
    }
    
    /**
     * 管理员登录处理
     */
    public function doLogin()
    {
        $username = $this->request->post('username', '', 'trim');
        $password = $this->request->post('password', '', 'trim');
        
        // 验证参数
        if (empty($username) || empty($password)) {
            return $this->error('请输入用户名和密码');
        }
        
        // 查找管理员
        $admin = Admin::where('username', $username)->find();
        if (!$admin) {
            return $this->error('用户名或密码错误');
        }
        
        if ($admin->status != 1) {
            return $this->error('账号已被禁用');
        }
        
        // 验证密码
        if (!password_verify($password, $admin->password)) {
            return $this->error('用户名或密码错误');
        }
        
        // 更新登录信息
        $admin->last_login_time = time();
        $admin->last_login_ip = get_real_ip();
        $admin->save();
        
        // 设置session
        Session::set('admin_id', $admin->id);
        Session::set('admin_info', [
            'id' => $admin->id,
            'username' => $admin->username,
            'nickname' => $admin->nickname,
            'avatar' => $admin->avatar,
        ]);
        
        return $this->success('登录成功', ['url' => (string)url('/admin')]);
    }
    
    /**
     * 退出登录
     */
    public function logout()
    {
        Session::delete('admin_id');
        Session::delete('admin_info');
        
        return redirect(url('/admin/login'));
    }
    
    /**
     * 后台首页
     */
    public function index()
    {
        $adminId = Session::get('admin_id');
        $adminInfo = Session::get('admin_info');
        
        // 统计数据
        $userCount = User::count();
        $promptCount = Prompt::count();
        $workflowCount = Workflow::count();
        $commentCount = Comment::count();
        
        // 待审核数量
        $pendingPromptCount = Prompt::where('status', Prompt::STATUS_PENDING)->count();
        $pendingWorkflowCount = Workflow::where('status', Workflow::STATUS_PENDING)->count();
        
        // 最近注册的用户
        $latestUsers = User::order('create_time', 'desc')
            ->limit(10)
            ->select()
            ->toArray();
        
        // 最近的评论
        $latestComments = Comment::with(['user'])
            ->order('create_time', 'desc')
            ->limit(10)
            ->select()
            ->toArray();
        
        View::assign([
            'adminId' => $adminId,
            'adminInfo' => $adminInfo,
            'userCount' => $userCount,
            'promptCount' => $promptCount,
            'workflowCount' => $workflowCount,
            'commentCount' => $commentCount,
            'pendingPromptCount' => $pendingPromptCount,
            'pendingWorkflowCount' => $pendingWorkflowCount,
            'latestUsers' => $latestUsers,
            'latestComments' => $latestComments,
        ]);
        
        return View::fetch('admin/index');
    }
}
