<?php

namespace app;

use think\App;
use think\exception\ValidateException;
use think\Validate;
use think\facade\Session;
use think\facade\View;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 当前登录用户ID
     * @var int|null
     */
    protected $userId = null;

    /**
     * 当前登录用户信息
     * @var array|null
     */
    protected $userInfo = null;

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();

        // 初始化用户信息
        $this->initUserInfo();
    }

    // 初始化
    protected function initialize()
    {}

    /**
     * 初始化用户信息并传递到视图
     */
    protected function initUserInfo()
    {
        $this->userId = Session::get('user_id');
        $this->userInfo = Session::get('user_info');
        
        View::assign([
            'userId' => $this->userId,
            'userInfo' => $this->userInfo,
        ]);
    }

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate  验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }
    
    /**
     * 成功响应
     * @param string $msg
     * @param array $data
     * @param int $code
     * @return \think\response\Json
     */
    protected function success($msg = '操作成功', $data = [], $code = 200)
    {
        return json(['code' => $code, 'msg' => $msg, 'data' => $data]);
    }
    
    /**
     * 失败响应
     * @param string $msg
     * @param int $code
     * @param array $data
     * @return \think\response\Json
     */
    protected function error($msg = '操作失败', $code = 400, $data = [])
    {
        return json(['code' => $code, 'msg' => $msg, 'data' => $data]);
    }
}
