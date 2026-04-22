<?php

namespace app\admin\controller;

use app\BaseController;
use app\model\Prompt;
use app\model\Category;
use app\model\User;
use think\facade\Session;
use think\facade\View;

/**
 * 管理后台 - 提示词管理控制器
 */
class PromptController extends BaseController
{
    // 中间件
    protected $middleware = [
        'admin_auth',
    ];
    
    /**
     * 提示词列表
     */
    public function index()
    {
        $adminId = Session::get('admin_id');
        $adminInfo = Session::get('admin_info');
        
        $page = $this->request->get('page', 1, 'intval');
        $pageSize = config('app.list_rows', 20);
        $keyword = $this->request->get('keyword', '', 'trim');
        $categoryId = $this->request->get('category_id', 0, 'intval');
        $status = $this->request->get('status', 0, 'intval');
        
        $query = Prompt::with(['user', 'category']);
        
        if (!empty($keyword)) {
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                  ->whereOr('description', 'like', '%' . $keyword . '%');
            });
        }
        
        if ($categoryId > 0) {
            $query->where('category_id', $categoryId);
        }
        
        if ($status > 0) {
            $query->where('status', $status);
        }
        
        $total = $query->count();
        $list = $query->order('id', 'desc')
            ->page($page, $pageSize)
            ->select()
            ->toArray();
        
        // 获取分类
        $categories = Category::getPromptCategories();
        
        View::assign([
            'adminId' => $adminId,
            'adminInfo' => $adminInfo,
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'pageSize' => $pageSize,
            'totalPage' => ceil($total / $pageSize),
            'keyword' => $keyword,
            'categoryId' => $categoryId,
            'status' => $status,
            'categories' => $categories,
        ]);
        
        return View::fetch('admin/prompt/index');
    }
    
    /**
     * 提示词详情
     */
    public function detail()
    {
        $adminId = Session::get('admin_id');
        $adminInfo = Session::get('admin_info');
        
        $id = $this->request->get('id', 0, 'intval');
        $prompt = Prompt::with(['user', 'category'])->find($id);
        
        if (!$prompt) {
            return redirect(url('/admin/prompt'));
        }
        
        View::assign([
            'adminId' => $adminId,
            'adminInfo' => $adminInfo,
            'prompt' => $prompt,
        ]);
        
        return View::fetch('admin/prompt/detail');
    }
    
    /**
     * 审核提示词
     */
    public function audit()
    {
        $id = $this->request->post('id', 0, 'intval');
        $status = $this->request->post('status', Prompt::STATUS_PASSED, 'intval');
        
        $prompt = Prompt::find($id);
        
        if (!$prompt) {
            return $this->error('提示词不存在');
        }
        
        // 如果审核通过，给用户发放积分奖励
        if ($status == Prompt::STATUS_PASSED && $prompt->status != Prompt::STATUS_PASSED) {
            $user = User::find($prompt->user_id);
            if ($user) {
                $pointsReward = $prompt->points_reward ?: config('app.publish_prompt_points', 2);
                $user->addPoints(
                    $pointsReward,
                    'publish_prompt',
                    $prompt->id,
                    '发布提示词【' . $prompt->title . '】审核通过，奖励' . $pointsReward . '积分'
                );
            }
        }
        
        $prompt->status = $status;
        $prompt->save();
        
        return $this->success('审核成功');
    }
    
    /**
     * 设置推荐
     */
    public function setRecommend()
    {
        $id = $this->request->post('id', 0, 'intval');
        $isRecommend = $this->request->post('is_recommend', 0, 'intval');
        
        $prompt = Prompt::find($id);
        
        if (!$prompt) {
            return $this->error('提示词不存在');
        }
        
        $prompt->is_recommend = $isRecommend;
        $prompt->save();
        
        return $this->success('操作成功');
    }
    
    /**
     * 删除提示词
     */
    public function delete()
    {
        $id = $this->request->post('id', 0, 'intval');
        
        $prompt = Prompt::find($id);
        
        if (!$prompt) {
            return $this->error('提示词不存在');
        }
        
        $prompt->status = Prompt::STATUS_DISABLED;
        $prompt->save();
        
        return $this->success('删除成功');
    }
}
