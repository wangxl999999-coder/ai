<?php

namespace app\admin\controller;

use app\BaseController;
use app\model\User;
use think\facade\Session;
use think\facade\View;

/**
 * 管理后台 - 用户管理控制器
 */
class UserController extends BaseController
{
    // 中间件
    protected $middleware = [
        'admin_auth',
    ];
    
    /**
     * 用户列表
     */
    public function index()
    {
        $adminId = Session::get('admin_id');
        $adminInfo = Session::get('admin_info');
        
        $page = $this->request->get('page', 1, 'intval');
        $pageSize = config('app.list_rows', 20);
        $keyword = $this->request->get('keyword', '', 'trim');
        $status = $this->request->get('status', 0, 'intval');
        
        $query = User::field('id,phone,nickname,avatar,points,status,last_login_time,create_time');
        
        if (!empty($keyword)) {
            $query->where(function($q) use ($keyword) {
                $q->where('phone', 'like', '%' . $keyword . '%')
                  ->whereOr('nickname', 'like', '%' . $keyword . '%');
            });
        }
        
        if ($status > 0) {
            $query->where('status', $status == 1 ? 1 : 0);
        }
        
        $total = $query->count();
        $list = $query->order('id', 'desc')
            ->page($page, $pageSize)
            ->select()
            ->toArray();
        
        View::assign([
            'adminId' => $adminId,
            'adminInfo' => $adminInfo,
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'pageSize' => $pageSize,
            'totalPage' => ceil($total / $pageSize),
            'keyword' => $keyword,
            'status' => $status,
        ]);
        
        return View::fetch('admin/user/index');
    }
    
    /**
     * 编辑用户
     */
    public function edit()
    {
        $adminId = Session::get('admin_id');
        $adminInfo = Session::get('admin_info');
        
        $id = $this->request->get('id', 0, 'intval');
        $user = User::find($id);
        
        if (!$user) {
            return redirect(url('/admin/user'));
        }
        
        View::assign([
            'adminId' => $adminId,
            'adminInfo' => $adminInfo,
            'user' => $user,
        ]);
        
        return View::fetch('admin/user/edit');
    }
    
    /**
     * 保存用户
     */
    public function save()
    {
        $id = $this->request->post('id', 0, 'intval');
        $nickname = $this->request->post('nickname', '', 'trim');
        $points = $this->request->post('points', 0, 'intval');
        $status = $this->request->post('status', 1, 'intval');
        
        $user = User::find($id);
        
        if (!$user) {
            return $this->error('用户不存在');
        }
        
        if (!empty($nickname)) {
            $user->nickname = $nickname;
        }
        
        // 积分变动需要记录
        if ($points != $user->points) {
            // 这里简化处理，实际项目中应该记录积分变动
            $user->points = $points;
        }
        
        $user->status = $status;
        $user->save();
        
        return $this->success('保存成功', ['url' => (string)url('/admin/user')]);
    }
    
    /**
     * 禁用/启用用户
     */
    public function toggleStatus()
    {
        $id = $this->request->post('id', 0, 'intval');
        
        $user = User::find($id);
        
        if (!$user) {
            return $this->error('用户不存在');
        }
        
        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();
        
        return $this->success('操作成功');
    }
    
    /**
     * 重置密码
     */
    public function resetPassword()
    {
        $id = $this->request->post('id', 0, 'intval');
        $password = $this->request->post('password', '', 'trim');
        
        if (strlen($password) < 6 || strlen($password) > 20) {
            return $this->error('密码长度应为6-20位');
        }
        
        $user = User::find($id);
        
        if (!$user) {
            return $this->error('用户不存在');
        }
        
        $user->password = password_encrypt($password);
        $user->save();
        
        return $this->success('密码重置成功');
    }
}
