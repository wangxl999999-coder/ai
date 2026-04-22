<?php

namespace app\controller;

use app\BaseController;
use app\model\Category;
use app\model\Prompt;
use app\model\User;
use app\model\Purchase;
use app\model\Favorite;
use app\model\Like;
use app\model\Comment;
use think\facade\Session;
use think\facade\View;

/**
 * 提示词控制器
 */
class PromptController extends BaseController
{
    // 中间件
    protected $middleware = [
        'auth' => ['except' => ['index', 'detail']],
    ];
    
    /**
     * 提示词列表
     */
    public function index()
    {
        $categoryId = $this->request->get('category_id', 0, 'intval');
        $page = $this->request->get('page', 1, 'intval');
        $pageSize = config('app.list_rows', 12);
        $sort = $this->request->get('sort', 'new', 'trim');
        
        $userId = Session::get('user_id');
        $userInfo = Session::get('user_info');
        
        // 获取分类
        $categories = Category::getPromptCategories();
        
        // 查询条件
        $query = Prompt::with(['user', 'category'])
            ->where('status', Prompt::STATUS_PASSED);
        
        if ($categoryId > 0) {
            $query->where('category_id', $categoryId);
        }
        
        // 排序
        switch ($sort) {
            case 'hot':
                $query->order('view_count', 'desc');
                break;
            case 'like':
                $query->order('like_count', 'desc');
                break;
            default:
                $query->order('create_time', 'desc');
        }
        
        $total = $query->count();
        $list = $query->page($page, $pageSize)
            ->select()
            ->toArray();
        
        // 添加用户互动状态
        if ($userId) {
            foreach ($list as &$item) {
                $prompt = Prompt::find($item['id']);
                $item['is_favorited'] = $prompt->isFavorited($userId);
                $item['is_liked'] = $prompt->isLiked($userId);
                $item['is_purchased'] = $prompt->isPurchased($userId);
            }
        }
        
        View::assign([
            'userId' => $userId,
            'userInfo' => $userInfo,
            'categories' => $categories,
            'categoryId' => $categoryId,
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'pageSize' => $pageSize,
            'totalPage' => ceil($total / $pageSize),
            'sort' => $sort,
        ]);
        
        return View::fetch('prompt/index');
    }
    
    /**
     * 提示词详情
     */
    public function detail()
    {
        $id = $this->request->get('id', 0, 'intval');
        
        $userId = Session::get('user_id');
        $userInfo = Session::get('user_info');
        
        // 查找提示词
        $prompt = Prompt::with(['user', 'category'])->find($id);
        
        if (!$prompt || $prompt->status != Prompt::STATUS_PASSED) {
            return redirect(url('/prompt'));
        }
        
        // 增加浏览次数
        $prompt->incrementViewCount();
        
        // 检查用户是否已购买
        $isPurchased = $userId ? $prompt->isPurchased($userId) : false;
        
        // 检查用户是否已收藏
        $isFavorited = $userId ? $prompt->isFavorited($userId) : false;
        
        // 检查用户是否已点赞
        $isLiked = $userId ? $prompt->isLiked($userId) : false;
        
        // 获取评论列表
        $comments = $prompt->comments()->with(['user', 'children'])->select()->toArray();
        
        // 处理评论的点赞状态
        if ($userId) {
            foreach ($comments as &$comment) {
                $commentModel = Comment::find($comment['id']);
                $comment['is_liked'] = $commentModel ? $commentModel->isLiked($userId) : false;
                
                // 子评论
                if (!empty($comment['children'])) {
                    foreach ($comment['children'] as &$child) {
                        $childModel = Comment::find($child['id']);
                        $child['is_liked'] = $childModel ? $childModel->isLiked($userId) : false;
                    }
                }
            }
        }
        
        // 获取相关推荐
        $relatedPrompts = Prompt::with(['user', 'category'])
            ->where('category_id', $prompt->category_id)
            ->where('id', '<>', $id)
            ->where('status', Prompt::STATUS_PASSED)
            ->order('create_time', 'desc')
            ->limit(6)
            ->select()
            ->toArray();
        
        View::assign([
            'userId' => $userId,
            'userInfo' => $userInfo,
            'prompt' => $prompt,
            'isPurchased' => $isPurchased,
            'isFavorited' => $isFavorited,
            'isLiked' => $isLiked,
            'comments' => $comments,
            'relatedPrompts' => $relatedPrompts,
        ]);
        
        return View::fetch('prompt/detail');
    }
    
    /**
     * 发布提示词页面
     */
    public function create()
    {
        $userId = Session::get('user_id');
        $userInfo = Session::get('user_info');
        
        // 获取分类
        $categories = Category::getPromptCategories();
        
        View::assign([
            'userId' => $userId,
            'userInfo' => $userInfo,
            'categories' => $categories,
        ]);
        
        return View::fetch('prompt/create');
    }
    
    /**
     * 编辑提示词页面
     */
    public function edit()
    {
        $id = $this->request->get('id', 0, 'intval');
        $userId = Session::get('user_id');
        $userInfo = Session::get('user_info');
        
        $prompt = Prompt::find($id);
        
        if (!$prompt || $prompt->user_id != $userId) {
            return redirect(url('/user/my_prompts'));
        }
        
        // 获取分类
        $categories = Category::getPromptCategories();
        
        View::assign([
            'userId' => $userId,
            'userInfo' => $userInfo,
            'prompt' => $prompt,
            'categories' => $categories,
        ]);
        
        return View::fetch('prompt/edit');
    }
    
    /**
     * 保存提示词
     */
    public function save()
    {
        $id = $this->request->post('id', 0, 'intval');
        $userId = Session::get('user_id');
        
        $title = $this->request->post('title', '', 'trim');
        $categoryId = $this->request->post('category_id', 0, 'intval');
        $description = $this->request->post('description', '', 'trim');
        $content = $this->request->post('content', '', 'trim');
        $tags = $this->request->post('tags', '', 'trim');
        
        // 验证参数
        if (empty($title)) {
            return $this->error('标题不能为空');
        }
        
        if ($categoryId <= 0) {
            return $this->error('请选择分类');
        }
        
        if (empty($content)) {
            return $this->error('内容不能为空');
        }
        
        if (mb_strlen($title, 'utf-8') > 100) {
            return $this->error('标题不能超过100个字符');
        }
        
        // 生成预览内容（前200字符）
        $preview = mb_substr(strip_tags($content), 0, 200, 'utf-8');
        
        if ($id > 0) {
            // 编辑
            $prompt = Prompt::find($id);
            if (!$prompt || $prompt->user_id != $userId) {
                return $this->error('无权操作');
            }
            
            $prompt->title = $title;
            $prompt->category_id = $categoryId;
            $prompt->description = $description;
            $prompt->content = $content;
            $prompt->preview = $preview;
            $prompt->tags = $tags;
            $prompt->save();
        } else {
            // 新建
            $pointsReward = config('app.publish_prompt_points', 2);
            
            $prompt = Prompt::create([
                'user_id' => $userId,
                'category_id' => $categoryId,
                'title' => $title,
                'description' => $description,
                'content' => $content,
                'preview' => $preview,
                'tags' => $tags,
                'status' => Prompt::STATUS_PENDING, // 待审核
                'is_recommend' => 0,
                'points_reward' => $pointsReward,
                'points_fee' => config('app.view_prompt_points', 1),
                'create_time' => time(),
                'update_time' => time(),
            ]);
        }
        
        return $this->success('保存成功', ['url' => (string)url('/user/my_prompts')]);
    }
    
    /**
     * 购买提示词
     */
    public function purchase()
    {
        $id = $this->request->post('id', 0, 'intval');
        $userId = Session::get('user_id');
        
        // 查找提示词
        $prompt = Prompt::find($id);
        
        if (!$prompt || $prompt->status != Prompt::STATUS_PASSED) {
            return $this->error('提示词不存在或已下架');
        }
        
        // 检查是否已购买
        if ($prompt->isPurchased($userId)) {
            return $this->success('您已购买过此提示词');
        }
        
        // 检查积分
        $user = User::find($userId);
        $pointsFee = $prompt->points_fee ?: config('app.view_prompt_points', 1);
        
        if ($user->points < $pointsFee) {
            return $this->error('积分不足，请发布内容获取更多积分');
        }
        
        // 扣除积分
        $user->deductPoints(
            $pointsFee,
            'view_prompt',
            $id,
            '购买查看提示词【' . $prompt->title . '】'
        );
        
        // 记录购买
        Purchase::create([
            'user_id' => $userId,
            'target_type' => 'prompt',
            'target_id' => $id,
            'points' => $pointsFee,
            'create_time' => time(),
        ]);
        
        return $this->success('购买成功');
    }
    
    /**
     * 收藏/取消收藏
     */
    public function toggleFavorite()
    {
        $id = $this->request->post('id', 0, 'intval');
        $userId = Session::get('user_id');
        
        // 查找提示词
        $prompt = Prompt::find($id);
        
        if (!$prompt) {
            return $this->error('提示词不存在');
        }
        
        // 检查是否已收藏
        $favorite = Favorite::where('user_id', $userId)
            ->where('target_type', 'prompt')
            ->where('target_id', $id)
            ->find();
        
        if ($favorite) {
            // 取消收藏
            $favorite->delete();
            $prompt->decrementFavoriteCount();
            return $this->success('已取消收藏', ['is_favorited' => false]);
        } else {
            // 添加收藏
            Favorite::create([
                'user_id' => $userId,
                'target_type' => 'prompt',
                'target_id' => $id,
                'create_time' => time(),
            ]);
            $prompt->incrementFavoriteCount();
            return $this->success('收藏成功', ['is_favorited' => true]);
        }
    }
    
    /**
     * 点赞/取消点赞
     */
    public function toggleLike()
    {
        $id = $this->request->post('id', 0, 'intval');
        $userId = Session::get('user_id');
        
        // 查找提示词
        $prompt = Prompt::find($id);
        
        if (!$prompt) {
            return $this->error('提示词不存在');
        }
        
        // 检查是否已点赞
        $like = Like::where('user_id', $userId)
            ->where('target_type', 'prompt')
            ->where('target_id', $id)
            ->find();
        
        if ($like) {
            // 取消点赞
            $like->delete();
            $prompt->decrementLikeCount();
            return $this->success('已取消点赞', ['is_liked' => false, 'like_count' => $prompt->like_count]);
        } else {
            // 添加点赞
            Like::create([
                'user_id' => $userId,
                'target_type' => 'prompt',
                'target_id' => $id,
                'create_time' => time(),
            ]);
            $prompt->incrementLikeCount();
            return $this->success('点赞成功', ['is_liked' => true, 'like_count' => $prompt->like_count]);
        }
    }
    
    /**
     * 发表评论
     */
    public function comment()
    {
        $id = $this->request->post('id', 0, 'intval');
        $userId = Session::get('user_id');
        $content = $this->request->post('content', '', 'trim');
        $parentId = $this->request->post('parent_id', 0, 'intval');
        $replyUserId = $this->request->post('reply_user_id', 0, 'intval');
        
        // 验证参数
        if (empty($content)) {
            return $this->error('评论内容不能为空');
        }
        
        if (mb_strlen($content, 'utf-8') > 500) {
            return $this->error('评论内容不能超过500个字符');
        }
        
        // 查找提示词
        $prompt = Prompt::find($id);
        
        if (!$prompt || $prompt->status != Prompt::STATUS_PASSED) {
            return $this->error('提示词不存在或已下架');
        }
        
        // 创建评论
        Comment::create([
            'user_id' => $userId,
            'target_type' => 'prompt',
            'target_id' => $id,
            'parent_id' => $parentId,
            'reply_user_id' => $replyUserId,
            'content' => $content,
            'like_count' => 0,
            'status' => 1,
            'create_time' => time(),
            'update_time' => time(),
        ]);
        
        // 增加评论数
        $prompt->incrementCommentCount();
        
        return $this->success('评论发表成功');
    }
}
