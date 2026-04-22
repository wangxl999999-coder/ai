<?php

namespace app\admin\controller;

use app\BaseController;
use app\model\Workflow;
use app\model\Category;
use app\model\User;
use think\facade\Session;
use think\facade\View;

/**
 * 管理后台 - 工作流管理控制器
 */
class WorkflowController extends BaseController
{
    // 中间件
    protected $middleware = [
        'admin_auth',
    ];
    
    /**
     * 工作流列表
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
        
        $query = Workflow::with(['user', 'category']);
        
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
        $categories = Category::getWorkflowCategories();
        
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
        
        return View::fetch('admin/workflow/index');
    }
    
    /**
     * 工作流详情
     */
    public function detail()
    {
        $adminId = Session::get('admin_id');
        $adminInfo = Session::get('admin_info');
        
        $id = $this->request->get('id', 0, 'intval');
        $workflow = Workflow::with(['user', 'category'])->find($id);
        
        if (!$workflow) {
            return redirect(url('/admin/workflow'));
        }
        
        View::assign([
            'adminId' => $adminId,
            'adminInfo' => $adminInfo,
            'workflow' => $workflow,
        ]);
        
        return View::fetch('admin/workflow/detail');
    }
    
    /**
     * 审核工作流
     */
    public function audit()
    {
        $id = $this->request->post('id', 0, 'intval');
        $status = $this->request->post('status', Workflow::STATUS_PASSED, 'intval');
        
        $workflow = Workflow::find($id);
        
        if (!$workflow) {
            return $this->error('工作流不存在');
        }
        
        // 如果审核通过，给用户发放积分奖励
        if ($status == Workflow::STATUS_PASSED && $workflow->status != Workflow::STATUS_PASSED) {
            $user = User::find($workflow->user_id);
            if ($user) {
                $pointsReward = $workflow->points_reward ?: config('app.publish_workflow_points', 3);
                $user->addPoints(
                    $pointsReward,
                    'publish_workflow',
                    $workflow->id,
                    '发布工作流【' . $workflow->title . '】审核通过，奖励' . $pointsReward . '积分'
                );
            }
        }
        
        $workflow->status = $status;
        $workflow->save();
        
        return $this->success('审核成功');
    }
    
    /**
     * 设置推荐
     */
    public function setRecommend()
    {
        $id = $this->request->post('id', 0, 'intval');
        $isRecommend = $this->request->post('is_recommend', 0, 'intval');
        
        $workflow = Workflow::find($id);
        
        if (!$workflow) {
            return $this->error('工作流不存在');
        }
        
        $workflow->is_recommend = $isRecommend;
        $workflow->save();
        
        return $this->success('操作成功');
    }
    
    /**
     * 删除工作流
     */
    public function delete()
    {
        $id = $this->request->post('id', 0, 'intval');
        
        $workflow = Workflow::find($id);
        
        if (!$workflow) {
            return $this->error('工作流不存在');
        }
        
        $workflow->status = Workflow::STATUS_DISABLED;
        $workflow->save();
        
        return $this->success('删除成功');
    }
}
