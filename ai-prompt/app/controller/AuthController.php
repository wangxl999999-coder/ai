<?php

namespace app\controller;

use app\BaseController;
use app\model\User;
use app\model\VerificationCode;
use think\facade\Session;
use think\facade\View;
use think\exception\ValidateException;

/**
 * 用户认证控制器
 */
class AuthController extends BaseController
{
    /**
     * 登录页面
     */
    public function login()
    {
        if (Session::has('user_id')) {
            return redirect(url('/'));
        }
        
        return View::fetch('auth/login');
    }
    
    /**
     * 登录处理
     */
    public function doLogin()
    {
        $phone = $this->request->post('phone', '', 'trim');
        $password = $this->request->post('password', '', 'trim');
        
        // 验证参数
        if (empty($phone) || empty($password)) {
            return $this->error('请输入手机号和密码');
        }
        
        if (!is_phone($phone)) {
            return $this->error('手机号格式不正确');
        }
        
        // 查找用户
        $user = User::where('phone', $phone)->find();
        if (!$user) {
            return $this->error('用户不存在');
        }
        
        if ($user->status != 1) {
            return $this->error('账号已被禁用，请联系管理员');
        }
        
        // 验证密码
        if (!password_verify($password, $user->password)) {
            return $this->error('密码错误');
        }
        
        // 更新登录信息
        $user->last_login_time = time();
        $user->last_login_ip = get_real_ip();
        $user->save();
        
        // 设置session
        Session::set('user_id', $user->id);
        Session::set('user_info', [
            'id' => $user->id,
            'phone' => $user->phone,
            'nickname' => $user->nickname,
            'avatar' => $user->avatar,
            'points' => $user->points,
        ]);
        
        return $this->success('登录成功', ['url' => (string)url('/')]);
    }
    
    /**
     * 注册页面
     */
    public function register()
    {
        if (Session::has('user_id')) {
            return redirect(url('/'));
        }
        
        return View::fetch('auth/register');
    }
    
    /**
     * 注册处理
     */
    public function doRegister()
    {
        $phone = $this->request->post('phone', '', 'trim');
        $code = $this->request->post('code', '', 'trim');
        $password = $this->request->post('password', '', 'trim');
        $confirmPassword = $this->request->post('confirm_password', '', 'trim');
        
        // 验证参数
        if (empty($phone) || empty($code) || empty($password)) {
            return $this->error('请填写完整信息');
        }
        
        if (!is_phone($phone)) {
            return $this->error('手机号格式不正确');
        }
        
        if (strlen($password) < 6 || strlen($password) > 20) {
            return $this->error('密码长度应为6-20位');
        }
        
        if ($password != $confirmPassword) {
            return $this->error('两次密码输入不一致');
        }
        
        // 检查手机号是否已注册
        if (User::where('phone', $phone)->find()) {
            return $this->error('该手机号已注册');
        }
        
        // 验证验证码
        if (!VerificationCode::check($phone, $code, VerificationCode::TYPE_REGISTER)) {
            return $this->error('验证码错误或已过期');
        }
        
        // 创建用户
        $defaultPoints = config('app.default_points', 10);
        $user = User::create([
            'phone' => $phone,
            'password' => password_encrypt($password),
            'nickname' => generate_nickname(),
            'avatar' => get_default_avatar(),
            'points' => $defaultPoints,
            'status' => 1,
            'last_login_time' => time(),
            'last_login_ip' => get_real_ip(),
            'create_time' => time(),
            'update_time' => time(),
        ]);
        
        // 记录注册积分
        $user->addPoints(
            $defaultPoints,
            'register',
            0,
            '注册赠送' . $defaultPoints . '积分'
        );
        
        // 设置session
        Session::set('user_id', $user->id);
        Session::set('user_info', [
            'id' => $user->id,
            'phone' => $user->phone,
            'nickname' => $user->nickname,
            'avatar' => $user->avatar,
            'points' => $user->points,
        ]);
        
        return $this->success('注册成功', ['url' => (string)url('/')]);
    }
    
    /**
     * 发送验证码
     */
    public function sendCode()
    {
        $phone = $this->request->post('phone', '', 'trim');
        $type = $this->request->post('type', 'register', 'trim');
        
        // 验证参数
        if (empty($phone)) {
            return $this->error('请输入手机号');
        }
        
        if (!is_phone($phone)) {
            return $this->error('手机号格式不正确');
        }
        
        // 验证类型
        if (!in_array($type, [VerificationCode::TYPE_REGISTER, VerificationCode::TYPE_RESET_PASSWORD])) {
            return $this->error('验证码类型错误');
        }
        
        // 注册时检查手机号是否已存在
        if ($type == VerificationCode::TYPE_REGISTER) {
            if (User::where('phone', $phone)->find()) {
                return $this->error('该手机号已注册');
            }
        } else {
            // 重置密码时检查手机号是否存在
            if (!User::where('phone', $phone)->find()) {
                return $this->error('该手机号未注册');
            }
        }
        
        // 检查发送频率（60秒内只能发送一次）
        $lastCode = VerificationCode::where('phone', $phone)
            ->where('type', $type)
            ->order('id', 'desc')
            ->find();
        
        if ($lastCode && time() - $lastCode->create_time < 60) {
            return $this->error('验证码发送太频繁，请稍后再试');
        }
        
        // 生成验证码
        $expireTime = config('app.code_expire_time', 300);
        $code = VerificationCode::generate($phone, $type, $expireTime);
        
        // 实际项目中这里需要调用短信服务商的API发送验证码
        // 这里暂时模拟发送，将验证码输出到日志或返回
        // 在开发环境中，可以直接返回验证码方便测试
        
        return $this->success('验证码发送成功', [
            'code' => $code, // 开发环境返回，生产环境请移除
            'expire_seconds' => $expireTime,
        ]);
    }
    
    /**
     * 退出登录
     */
    public function logout()
    {
        Session::delete('user_id');
        Session::delete('user_info');
        
        return redirect(url('/user/login'));
    }
    
    /**
     * 忘记密码页面
     */
    public function forgotPassword()
    {
        if (Session::has('user_id')) {
            return redirect(url('/'));
        }
        
        return View::fetch('auth/forgot_password');
    }
    
    /**
     * 重置密码处理
     */
    public function doResetPassword()
    {
        $phone = $this->request->post('phone', '', 'trim');
        $code = $this->request->post('code', '', 'trim');
        $password = $this->request->post('password', '', 'trim');
        $confirmPassword = $this->request->post('confirm_password', '', 'trim');
        
        // 验证参数
        if (empty($phone) || empty($code) || empty($password)) {
            return $this->error('请填写完整信息');
        }
        
        if (!is_phone($phone)) {
            return $this->error('手机号格式不正确');
        }
        
        if (strlen($password) < 6 || strlen($password) > 20) {
            return $this->error('密码长度应为6-20位');
        }
        
        if ($password != $confirmPassword) {
            return $this->error('两次密码输入不一致');
        }
        
        // 查找用户
        $user = User::where('phone', $phone)->find();
        if (!$user) {
            return $this->error('用户不存在');
        }
        
        // 验证验证码
        if (!VerificationCode::check($phone, $code, VerificationCode::TYPE_RESET_PASSWORD)) {
            return $this->error('验证码错误或已过期');
        }
        
        // 更新密码
        $user->password = password_encrypt($password);
        $user->save();
        
        return $this->success('密码重置成功', ['url' => (string)url('/user/login')]);
    }
}
