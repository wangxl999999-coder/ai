<?php

namespace app\admin\controller;

use app\BaseController;
use app\model\Category;
use think\facade\Session;
use think\facade\View;

/**
 * 管理后台 - 分类管理控制器
 */
class CategoryController extends BaseController
{
    // 中间件
    protected $middleware = [
        'admin_auth',
    ];
    
    /**
     * 分类列表
     */
    public function index()
    {
        $adminId = Session::get('admin_id');
        $adminInfo = Session::get('admin_info');
        
        $type = $this->request->get('type', 1, 'intval');
        
        $list = Category::where('type', $type)
            ->order('sort', 'asc')
            ->select()
            ->toArray();
        
        View::assign([
            'adminId' => $adminId,
            'adminInfo' => $adminInfo,
            'list' => $list,
            'type' => $type,
        ]);
        
        return View::fetch('admin/category/index');
    }
    
    /**
     * 添加分类
     */
    public function create()
    {
        $adminId = Session::get('admin_id');
        $adminInfo = Session::get('admin_info');
        
        $type = $this->request->get('type', 1, 'intval');
        
        View::assign([
            'adminId' => $adminId,
            'adminInfo' => $adminInfo,
            'type' => $type,
        ]);
        
        return View::fetch('admin/category/edit');
    }
    
    /**
     * 编辑分类
     */
    public function edit()
    {
        $adminId = Session::get('admin_id');
        $adminInfo = Session::get('admin_info');
        
        $id = $this->request->get('id', 0, 'intval');
        $category = Category::find($id);
        
        if (!$category) {
            return redirect(url('/admin/category'));
        }
        
        View::assign([
            'adminId' => $adminId,
            'adminInfo' => $adminInfo,
            'category' => $category,
        ]);
        
        return View::fetch('admin/category/edit');
    }
    
    /**
     * 保存分类
     */
    public function save()
    {
        $id = $this->request->post('id', 0, 'intval');
        $name = $this->request->post('name', '', 'trim');
        $type = $this->request->post('type', 1, 'intval');
        $sort = $this->request->post('sort', 0, 'intval');
        $status = $this->request->post('status', 1, 'intval');
        
        // 验证参数
        if (empty($name)) {
            return $this->error('分类名称不能为空');
        }
        
        if ($id > 0) {
            // 编辑
            $category = Category::find($id);
            if (!$category) {
                return $this->error('分类不存在');
            }
            
            $category->name = $name;
            $category->sort = $sort;
            $category->status = $status;
            $category->save();
        } else {
            // 新建
            Category::create([
                'name' => $name,
                'type' => $type,
                'sort' => $sort,
                'status' => $status,
                'create_time' => time(),
                'update_time' => time(),
            ]);
        }
        
        return $this->success('保存成功', ['url' => (string)url('/admin/category', ['type' => $type])]);
    }
    
    /**
     * 删除分类
     */
    public function delete()
    {
        $id = $this->request->post('id', 0, 'intval');
        
        $category = Category::find($id);
        
        if (!$category) {
            return $this->error('分类不存在');
        }
        
        $category->delete();
        
        return $this->success('删除成功');
    }
    
    /**
     * 排序
     */
    public function sort()
    {
        $id = $this->request->post('id', 0, 'intval');
        $sort = $this->request->post('sort', 0, 'intval');
        
        $category = Category::find($id);
        
        if (!$category) {
            return $this->error('分类不存在');
        }
        
        $category->sort = $sort;
        $category->save();
        
        return $this->success('排序成功');
    }
}
