<?php

namespace app\admin\controller;

use app\BaseController;
use app\model\Comment;
use app\model\Prompt;
use app\model\Workflow;
use think\facade\Session;
use think\facade\View;

/**
 * 管理后台 - 评论管理控制器
 */
class CommentController extends BaseController
{
    // 中间件
    protected $middleware = [
        'admin_auth',
    ];
    
    /**
     * 评论列表
     */
    public function index()
    {
        $adminId = Session::get('admin_id');
        $adminInfo = Session::get('admin_info');
        
        $page = $this->request->get('page', 1, 'intval');
        $pageSize = config('app.list_rows', 20);
        $keyword = $this->request->get('keyword', '', 'trim');
        $targetType = $this->request->get('target_type', '', 'trim');
        $status = $this->request->get('status', 0, 'intval');
        
        $query = Comment::with(['user']);
        
        if (!empty($keyword)) {
            $query->where('content', 'like', '%' . $keyword . '%');
        }
        
        if (!empty($targetType)) {
            $query->where('target_type', $targetType);
        }
        
        if ($status > 0) {
            $query->where('status', $status == 1 ? 1 : 0);
        }
        
        $total = $query->count();
        $list = $query->order('id', 'desc')
            ->page($page, $pageSize)
            ->select()
            ->toArray();
        
        // 补充目标信息
        foreach ($list as &$item) {
            if ($item['target_type'] == 'prompt') {
                $prompt = Prompt::find($item['target_id']);
                $item['target_title'] = $prompt ? $prompt->title : '已删除';
            } else {
                $workflow = Workflow::find($item['target_id']);
                $item['target_title'] = $workflow ? $workflow->title : '已删除';
            }
        }
        
        View::assign([
            'adminId' => $adminId,
            'adminInfo' => $adminInfo,
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'pageSize' => $pageSize,
            'totalPage' => ceil($total / $pageSize),
            'keyword' => $keyword,
            'targetType' => $targetType,
            'status' => $status,
        ]);
        
        return View::fetch('admin/comment/index');
    }
    
    /**
     * 评论详情
     */
    public function detail()
    {
        $adminId = Session::get('admin_id');
        $adminInfo = Session::get('admin_info');
        
        $id = $this->request->get('id', 0, 'intval');
        $comment = Comment::with(['user'])->find($id);
        
        if (!$comment) {
            return redirect(url('/admin/comment'));
        }
        
        // 补充目标信息
        if ($comment->target_type == 'prompt') {
            $prompt = Prompt::find($comment->target_id);
            $comment->target_title = $prompt ? $prompt->title : '已删除';
        } else {
            $workflow = Workflow::find($comment->target_id);
            $comment->target_title = $workflow ? $workflow->title : '已删除';
        }
        
        // 获取子评论
        $children = Comment::with(['user'])
            ->where('parent_id', $id)
            ->order('create_time', 'asc')
            ->select()
            ->toArray();
        
        View::assign([
            'adminId' => $adminId,
            'adminInfo' => $adminInfo,
            'comment' => $comment,
            'children' => $children,
        ]);
        
        return View::fetch('admin/comment/detail');
    }
    
    /**
     * 审核评论
     */
    public function audit()
    {
        $id = $this->request->post('id', 0, 'intval');
        $status = $this->request->post('status', 1, 'intval');
        
        $comment = Comment::find($id);
        
        if (!$comment) {
            return $this->error('评论不存在');
        }
        
        $comment->status = $status;
        $comment->save();
        
        return $this->success('审核成功');
    }
    
    /**
     * 删除评论
     */
    public function delete()
    {
        $id = $this->request->post('id', 0, 'intval');
        
        $comment = Comment::find($id);
        
        if (!$comment) {
            return $this->error('评论不存在');
        }
        
        // 删除关联的点赞
        \app\model\Like::where('target_type', 'comment')
            ->where('target_id', $id)
            ->delete();
        
        // 删除子评论
        Comment::where('parent_id', $id)->delete();
        
        $comment->delete();
        
        return $this->success('删除成功');
    }
}
