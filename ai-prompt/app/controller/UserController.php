<?php

namespace app\controller;

use app\BaseController;
use app\model\User;
use app\model\Prompt;
use app\model\Workflow;
use app\model\Comment;
use app\model\Favorite;
use app\model\PointsRecord;
use think\facade\Session;
use think\facade\View;

/**
 * 用户中心控制器
 */
class UserController extends BaseController
{
    // 中间件
    protected $middleware = [
        'auth' => ['except' => ['login', 'register', 'forgotPassword']],
    ];
    
    /**
     * 个人中心首页
     */
    public function index()
    {
        $userId = Session::get('user_id');
        $user = User::find($userId);
        
        // 统计数据
        $promptCount = Prompt::where('user_id', $userId)->count();
        $workflowCount = Workflow::where('user_id', $userId)->count();
        $favoriteCount = Favorite::where('user_id', $userId)->count();
        $commentCount = Comment::where('user_id', $userId)->count();
        
        // 最近的积分记录
        $pointsRecords = PointsRecord::where('user_id', $userId)
            ->order('create_time', 'desc')
            ->limit(10)
            ->select()
            ->toArray();
        
        $userInfo = Session::get('user_info');
        
        View::assign([
            'userId' => $userId,
            'userInfo' => $userInfo,
            'user' => $user,
            'promptCount' => $promptCount,
            'workflowCount' => $workflowCount,
            'favoriteCount' => $favoriteCount,
            'commentCount' => $commentCount,
            'pointsRecords' => $pointsRecords,
        ]);
        
        return View::fetch('user/index');
    }
    
    /**
     * 个人设置页面
     */
    public function profile()
    {
        $userId = Session::get('user_id');
        $user = User::find($userId);
        $userInfo = Session::get('user_info');
        
        View::assign([
            'userId' => $userId,
            'userInfo' => $userInfo,
            'user' => $user,
        ]);
        
        return View::fetch('user/profile');
    }
    
    /**
     * 更新个人信息
     */
    public function updateProfile()
    {
        $userId = Session::get('user_id');
        $nickname = $this->request->post('nickname', '', 'trim');
        $avatar = $this->request->post('avatar', '', 'trim');
        
        // 验证参数
        if (empty($nickname)) {
            return $this->error('昵称不能为空');
        }
        
        if (mb_strlen($nickname, 'utf-8') > 20) {
            return $this->error('昵称不能超过20个字符');
        }
        
        $user = User::find($userId);
        $user->nickname = $nickname;
        
        if (!empty($avatar)) {
            $user->avatar = $avatar;
        }
        
        $user->save();
        
        // 更新session
        $userInfo = Session::get('user_info');
        $userInfo['nickname'] = $user->nickname;
        $userInfo['avatar'] = $user->avatar;
        Session::set('user_info', $userInfo);
        
        return $this->success('更新成功');
    }
    
    /**
     * 修改密码页面
     */
    public function password()
    {
        $userId = Session::get('user_id');
        $userInfo = Session::get('user_info');
        
        View::assign([
            'userId' => $userId,
            'userInfo' => $userInfo,
        ]);
        
        return View::fetch('user/password');
    }
    
    /**
     * 修改密码处理
     */
    public function updatePassword()
    {
        $userId = Session::get('user_id');
        $oldPassword = $this->request->post('old_password', '', 'trim');
        $newPassword = $this->request->post('new_password', '', 'trim');
        $confirmPassword = $this->request->post('confirm_password', '', 'trim');
        
        // 验证参数
        if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
            return $this->error('请填写完整信息');
        }
        
        if (strlen($newPassword) < 6 || strlen($newPassword) > 20) {
            return $this->error('密码长度应为6-20位');
        }
        
        if ($newPassword != $confirmPassword) {
            return $this->error('两次密码输入不一致');
        }
        
        $user = User::find($userId);
        
        // 验证旧密码
        if (!password_verify($oldPassword, $user->password)) {
            return $this->error('原密码错误');
        }
        
        // 更新密码
        $user->password = password_encrypt($newPassword);
        $user->save();
        
        return $this->success('密码修改成功');
    }
    
    /**
     * 我的提示词
     */
    public function myPrompts()
    {
        $userId = Session::get('user_id');
        $page = $this->request->get('page', 1, 'intval');
        $pageSize = config('app.list_rows', 10);
        $status = $this->request->get('status', 0, 'intval');
        
        $query = Prompt::with(['category'])
            ->where('user_id', $userId);
        
        if ($status > 0) {
            $query->where('status', $status);
        }
        
        $total = $query->count();
        $list = $query->order('create_time', 'desc')
            ->page($page, $pageSize)
            ->select()
            ->toArray();
        
        $userInfo = Session::get('user_info');
        
        View::assign([
            'userId' => $userId,
            'userInfo' => $userInfo,
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'pageSize' => $pageSize,
            'totalPage' => ceil($total / $pageSize),
            'status' => $status,
        ]);
        
        return View::fetch('user/my_prompts');
    }
    
    /**
     * 我的工作流
     */
    public function myWorkflows()
    {
        $userId = Session::get('user_id');
        $page = $this->request->get('page', 1, 'intval');
        $pageSize = config('app.list_rows', 10);
        $status = $this->request->get('status', 0, 'intval');
        
        $query = Workflow::with(['category'])
            ->where('user_id', $userId);
        
        if ($status > 0) {
            $query->where('status', $status);
        }
        
        $total = $query->count();
        $list = $query->order('create_time', 'desc')
            ->page($page, $pageSize)
            ->select()
            ->toArray();
        
        $userInfo = Session::get('user_info');
        
        View::assign([
            'userId' => $userId,
            'userInfo' => $userInfo,
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'pageSize' => $pageSize,
            'totalPage' => ceil($total / $pageSize),
            'status' => $status,
        ]);
        
        return View::fetch('user/my_workflows');
    }
    
    /**
     * 我的收藏
     */
    public function myFavorites()
    {
        $userId = Session::get('user_id');
        $page = $this->request->get('page', 1, 'intval');
        $pageSize = config('app.list_rows', 10);
        $type = $this->request->get('type', 'prompt', 'trim');
        
        $favorites = Favorite::where('user_id', $userId)
            ->where('target_type', $type)
            ->order('create_time', 'desc')
            ->page($page, $pageSize)
            ->select();
        
        $list = [];
        foreach ($favorites as $favorite) {
            $target = $favorite->target;
            if ($target) {
                $item = $target->toArray();
                $item['favorite_id'] = $favorite->id;
                $list[] = $item;
            }
        }
        
        $total = Favorite::where('user_id', $userId)
            ->where('target_type', $type)
            ->count();
        
        $userInfo = Session::get('user_info');
        
        View::assign([
            'userId' => $userId,
            'userInfo' => $userInfo,
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'pageSize' => $pageSize,
            'totalPage' => ceil($total / $pageSize),
            'type' => $type,
        ]);
        
        return View::fetch('user/my_favorites');
    }
    
    /**
     * 我的评论
     */
    public function myComments()
    {
        $userId = Session::get('user_id');
        $page = $this->request->get('page', 1, 'intval');
        $pageSize = config('app.list_rows', 10);
        
        $list = Comment::with(['user'])
            ->where('user_id', $userId)
            ->order('create_time', 'desc')
            ->page($page, $pageSize)
            ->select()
            ->toArray();
        
        $total = Comment::where('user_id', $userId)->count();
        $userInfo = Session::get('user_info');
        
        View::assign([
            'userId' => $userId,
            'userInfo' => $userInfo,
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'pageSize' => $pageSize,
            'totalPage' => ceil($total / $pageSize),
        ]);
        
        return View::fetch('user/my_comments');
    }
    
    /**
     * 积分记录
     */
    public function pointsRecords()
    {
        $userId = Session::get('user_id');
        $page = $this->request->get('page', 1, 'intval');
        $pageSize = config('app.list_rows', 10);
        $type = $this->request->get('type', 0, 'intval');
        
        $query = PointsRecord::where('user_id', $userId);
        
        if ($type > 0) {
            $query->where('type', $type);
        }
        
        $total = $query->count();
        $list = $query->order('create_time', 'desc')
            ->page($page, $pageSize)
            ->select()
            ->toArray();
        
        $user = User::find($userId);
        $userInfo = Session::get('user_info');
        
        View::assign([
            'userId' => $userId,
            'userInfo' => $userInfo,
            'user' => $user,
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'pageSize' => $pageSize,
            'totalPage' => ceil($total / $pageSize),
            'type' => $type,
        ]);
        
        return View::fetch('user/points_records');
    }
}
