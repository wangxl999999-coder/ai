<?php

namespace app\controller;

use app\BaseController;
use app\model\Category;
use app\model\Prompt;
use app\model\Workflow;
use think\facade\Session;
use think\facade\View;

/**
 * 首页控制器
 */
class IndexController extends BaseController
{
    /**
     * 首页
     */
    public function index()
    {
        // 获取推荐提示词
        $recommendPrompts = Prompt::with(['user', 'category'])
            ->where('status', Prompt::STATUS_PASSED)
            ->where('is_recommend', 1)
            ->order('create_time', 'desc')
            ->limit(8)
            ->select()
            ->toArray();
        
        // 获取推荐工作流
        $recommendWorkflows = Workflow::with(['user', 'category'])
            ->where('status', Workflow::STATUS_PASSED)
            ->where('is_recommend', 1)
            ->order('create_time', 'desc')
            ->limit(8)
            ->select()
            ->toArray();
        
        // 获取最新提示词
        $latestPrompts = Prompt::with(['user', 'category'])
            ->where('status', Prompt::STATUS_PASSED)
            ->order('create_time', 'desc')
            ->limit(12)
            ->select()
            ->toArray();
        
        // 获取提示词分类
        $promptCategories = Category::getPromptCategories();
        
        // 获取工作流分类
        $workflowCategories = Category::getWorkflowCategories();
        
        // 获取用户信息
        $userId = Session::get('user_id');
        $userInfo = Session::get('user_info');
        
        View::assign([
            'recommendPrompts' => $recommendPrompts,
            'recommendWorkflows' => $recommendWorkflows,
            'latestPrompts' => $latestPrompts,
            'promptCategories' => $promptCategories,
            'workflowCategories' => $workflowCategories,
            'userId' => $userId,
            'userInfo' => $userInfo,
        ]);
        
        return View::fetch('index/index');
    }
    
    /**
     * 提示词写作技巧页面
     */
    public function promptTips()
    {
        $userId = Session::get('user_id');
        $userInfo = Session::get('user_info');
        
        View::assign([
            'userId' => $userId,
            'userInfo' => $userInfo,
        ]);
        
        return View::fetch('index/prompt_tips');
    }
    
    /**
     * AI工作流搭建技巧页面
     */
    public function workflowTips()
    {
        $userId = Session::get('user_id');
        $userInfo = Session::get('user_info');
        
        View::assign([
            'userId' => $userId,
            'userInfo' => $userInfo,
        ]);
        
        return View::fetch('index/workflow_tips');
    }
    
    /**
     * 搜索页面
     */
    public function search()
    {
        $keyword = $this->request->get('keyword', '', 'trim');
        $type = $this->request->get('type', 'prompt', 'trim');
        $categoryId = $this->request->get('category_id', 0, 'intval');
        $page = $this->request->get('page', 1, 'intval');
        $pageSize = config('app.list_rows', 10);
        
        $userId = Session::get('user_id');
        $userInfo = Session::get('user_info');
        
        // 获取分类
        $promptCategories = Category::getPromptCategories();
        $workflowCategories = Category::getWorkflowCategories();
        
        $list = [];
        $total = 0;
        
        if ($type == 'prompt') {
            // 搜索提示词
            $query = Prompt::with(['user', 'category'])
                ->where('status', Prompt::STATUS_PASSED);
            
            if (!empty($keyword)) {
                $query->where(function($q) use ($keyword) {
                    $q->where('title', 'like', '%' . $keyword . '%')
                      ->whereOr('description', 'like', '%' . $keyword . '%')
                      ->whereOr('tags', 'like', '%' . $keyword . '%');
                });
            }
            
            if ($categoryId > 0) {
                $query->where('category_id', $categoryId);
            }
            
            $total = $query->count();
            $list = $query->order('create_time', 'desc')
                ->page($page, $pageSize)
                ->select()
                ->toArray();
        } else {
            // 搜索工作流
            $query = Workflow::with(['user', 'category'])
                ->where('status', Workflow::STATUS_PASSED);
            
            if (!empty($keyword)) {
                $query->where(function($q) use ($keyword) {
                    $q->where('title', 'like', '%' . $keyword . '%')
                      ->whereOr('description', 'like', '%' . $keyword . '%')
                      ->whereOr('tags', 'like', '%' . $keyword . '%');
                });
            }
            
            if ($categoryId > 0) {
                $query->where('category_id', $categoryId);
            }
            
            $total = $query->count();
            $list = $query->order('create_time', 'desc')
                ->page($page, $pageSize)
                ->select()
                ->toArray();
        }
        
        View::assign([
            'userId' => $userId,
            'userInfo' => $userInfo,
            'keyword' => $keyword,
            'type' => $type,
            'categoryId' => $categoryId,
            'promptCategories' => $promptCategories,
            'workflowCategories' => $workflowCategories,
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'pageSize' => $pageSize,
            'totalPage' => ceil($total / $pageSize),
        ]);
        
        return View::fetch('index/search');
    }
}
