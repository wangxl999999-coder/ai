<?php
// 应用路由
use think\facade\Route;

// 首页
Route::get('/', 'IndexController/index');
Route::get('/prompt-tips', 'IndexController/promptTips');
Route::get('/workflow-tips', 'IndexController/workflowTips');
Route::get('/search', 'IndexController/search');

// 用户认证
Route::get('/user/login', 'AuthController/login');
Route::post('/user/do-login', 'AuthController/doLogin');
Route::get('/user/register', 'AuthController/register');
Route::post('/user/do-register', 'AuthController/doRegister');
Route::post('/user/send-code', 'AuthController/sendCode');
Route::get('/user/logout', 'AuthController/logout');
Route::get('/user/forgot-password', 'AuthController/forgotPassword');
Route::post('/user/do-reset-password', 'AuthController/doResetPassword');

// 用户中心
Route::get('/user', 'UserController/index')->middleware('auth');
Route::get('/user/profile', 'UserController/profile')->middleware('auth');
Route::post('/user/update-profile', 'UserController/updateProfile')->middleware('auth');
Route::get('/user/password', 'UserController/password')->middleware('auth');
Route::post('/user/update-password', 'UserController/updatePassword')->middleware('auth');
Route::get('/user/my-prompts', 'UserController/myPrompts')->middleware('auth');
Route::get('/user/my-workflows', 'UserController/myWorkflows')->middleware('auth');
Route::get('/user/my-favorites', 'UserController/myFavorites')->middleware('auth');
Route::get('/user/my-comments', 'UserController/myComments')->middleware('auth');
Route::get('/user/points-records', 'UserController/pointsRecords')->middleware('auth');

// 提示词
Route::get('/prompt', 'PromptController/index');
Route::get('/prompt/detail', 'PromptController/detail');
Route::get('/prompt/create', 'PromptController/create')->middleware('auth');
Route::get('/prompt/edit', 'PromptController/edit')->middleware('auth');
Route::post('/prompt/save', 'PromptController/save')->middleware('auth');
Route::post('/prompt/purchase', 'PromptController/purchase')->middleware('auth');
Route::post('/prompt/toggle-favorite', 'PromptController/toggleFavorite')->middleware('auth');
Route::post('/prompt/toggle-like', 'PromptController/toggleLike')->middleware('auth');
Route::post('/prompt/comment', 'PromptController/comment')->middleware('auth');

// 工作流
Route::get('/workflow', 'WorkflowController/index');
Route::get('/workflow/detail', 'WorkflowController/detail');
Route::get('/workflow/create', 'WorkflowController/create')->middleware('auth');
Route::get('/workflow/edit', 'WorkflowController/edit')->middleware('auth');
Route::post('/workflow/save', 'WorkflowController/save')->middleware('auth');
Route::post('/workflow/purchase', 'WorkflowController/purchase')->middleware('auth');
Route::post('/workflow/toggle-favorite', 'WorkflowController/toggleFavorite')->middleware('auth');
Route::post('/workflow/toggle-like', 'WorkflowController/toggleLike')->middleware('auth');
Route::post('/workflow/comment', 'WorkflowController/comment')->middleware('auth');

// 管理后台
Route::get('/admin/login', 'admin\AdminController/login');
Route::post('/admin/do-login', 'admin\AdminController/doLogin');
Route::get('/admin/logout', 'admin\AdminController/logout')->middleware('admin_auth');
Route::get('/admin', 'admin\AdminController/index')->middleware('admin_auth');

// 管理后台 - 用户管理
Route::get('/admin/user', 'admin\UserController/index')->middleware('admin_auth');
Route::get('/admin/user/edit', 'admin\UserController/edit')->middleware('admin_auth');
Route::post('/admin/user/save', 'admin\UserController/save')->middleware('admin_auth');
Route::post('/admin/user/toggle-status', 'admin\UserController/toggleStatus')->middleware('admin_auth');
Route::post('/admin/user/reset-password', 'admin\UserController/resetPassword')->middleware('admin_auth');

// 管理后台 - 提示词管理
Route::get('/admin/prompt', 'admin\PromptController/index')->middleware('admin_auth');
Route::get('/admin/prompt/detail', 'admin\PromptController/detail')->middleware('admin_auth');
Route::post('/admin/prompt/audit', 'admin\PromptController/audit')->middleware('admin_auth');
Route::post('/admin/prompt/set-recommend', 'admin\PromptController/setRecommend')->middleware('admin_auth');
Route::post('/admin/prompt/delete', 'admin\PromptController/delete')->middleware('admin_auth');

// 管理后台 - 工作流管理
Route::get('/admin/workflow', 'admin\WorkflowController/index')->middleware('admin_auth');
Route::get('/admin/workflow/detail', 'admin\WorkflowController/detail')->middleware('admin_auth');
Route::post('/admin/workflow/audit', 'admin\WorkflowController/audit')->middleware('admin_auth');
Route::post('/admin/workflow/set-recommend', 'admin\WorkflowController/setRecommend')->middleware('admin_auth');
Route::post('/admin/workflow/delete', 'admin\WorkflowController/delete')->middleware('admin_auth');

// 管理后台 - 分类管理
Route::get('/admin/category', 'admin\CategoryController/index')->middleware('admin_auth');
Route::get('/admin/category/create', 'admin\CategoryController/create')->middleware('admin_auth');
Route::get('/admin/category/edit', 'admin\CategoryController/edit')->middleware('admin_auth');
Route::post('/admin/category/save', 'admin\CategoryController/save')->middleware('admin_auth');
Route::post('/admin/category/delete', 'admin\CategoryController/delete')->middleware('admin_auth');
Route::post('/admin/category/sort', 'admin\CategoryController/sort')->middleware('admin_auth');

// 管理后台 - 评论管理
Route::get('/admin/comment', 'admin\CommentController/index')->middleware('admin_auth');
Route::get('/admin/comment/detail', 'admin\CommentController/detail')->middleware('admin_auth');
Route::post('/admin/comment/audit', 'admin\CommentController/audit')->middleware('admin_auth');
Route::post('/admin/comment/delete', 'admin\CommentController/delete')->middleware('admin_auth');
