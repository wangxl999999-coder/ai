<?php /*a:2:{s:52:"D:\my\tare-git\ai\ai-prompt\app\view\auth\login.html";i:1776826734;s:53:"D:\my\tare-git\ai\ai-prompt\app\view\layout\base.html";i:1776845020;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlentities((string) (isset($title) && ($title !== '')?$title:'AI提示词 - 创意灵感平台')); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/static/css/style.css" rel="stylesheet">
    
</head>
<body>
    <!-- 导航栏 -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="/">
                <i class="bi bi-stars"></i> AI提示词
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php if(app('request')->controller() == 'Index'): ?>active<?php endif; ?>" href="/">首页</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if(app('request')->controller() == 'Prompt'): ?>active<?php endif; ?>" href="/prompt">提示词</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if(app('request')->controller() == 'Workflow'): ?>active<?php endif; ?>" href="/workflow">工作流</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="tipsDropdown" role="button" data-bs-toggle="dropdown">
                            技巧教程
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/prompt-tips">提示词写作技巧</a></li>
                            <li><a class="dropdown-item" href="/workflow-tips">工作流搭建技巧</a></li>
                        </ul>
                    </li>
                </ul>
                
                <!-- 搜索框 -->
                <form class="d-flex me-3" action="/search" method="get">
                    <div class="input-group">
                        <select class="form-select form-select-sm" name="type" style="width: auto;">
                            <option value="prompt">提示词</option>
                            <option value="workflow">工作流</option>
                        </select>
                        <input class="form-control form-control-sm" type="search" name="keyword" placeholder="搜索..." style="width: 200px;">
                        <button class="btn btn-outline-primary btn-sm" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
                
                <!-- 用户菜单 -->
                <ul class="navbar-nav">
                    <?php if($userId): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <img src="<?php echo htmlentities((string) $userInfo['avatar']); ?>" class="rounded-circle" width="28" height="28" alt="avatar">
                                <span class="ms-1"><?php echo htmlentities((string) $userInfo['nickname']); ?></span>
                                <span class="badge bg-primary rounded-pill ms-1"><?php echo htmlentities((string) $userInfo['points']); ?>积分</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/user"><i class="bi bi-person me-2"></i>个人中心</a></li>
                                <li><a class="dropdown-item" href="/user/my-prompts"><i class="bi bi-file-text me-2"></i>我的提示词</a></li>
                                <li><a class="dropdown-item" href="/user/my-workflows"><i class="bi bi-diagram-3 me-2"></i>我的工作流</a></li>
                                <li><a class="dropdown-item" href="/user/my-favorites"><i class="bi bi-heart me-2"></i>我的收藏</a></li>
                                <li><a class="dropdown-item" href="/user/points-records"><i class="bi bi-coin me-2"></i>积分记录</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/prompt/create"><i class="bi bi-plus-circle me-2"></i>发布提示词</a></li>
                                <li><a class="dropdown-item" href="/workflow/create"><i class="bi bi-plus-circle me-2"></i>发布工作流</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/user/profile"><i class="bi bi-gear me-2"></i>设置</a></li>
                                <li><a class="dropdown-item text-danger" href="/user/logout"><i class="bi bi-box-arrow-right me-2"></i>退出登录</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/user/login">登录</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm rounded-pill ms-2" href="/user/register">免费注册</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- 主内容 -->
    <main class="py-4">
        
<div class="container">
    <div class="row justify-content-center py-5">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-stars text-primary fs-1"></i>
                        <h3 class="mt-2">用户登录</h3>
                        <p class="text-muted small">欢迎回来，继续您的创意之旅</p>
                    </div>
                    
                    <form id="loginForm">
                        <div class="mb-3">
                            <label class="form-label">手机号</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                <input type="tel" class="form-control" name="phone" placeholder="请输入手机号" maxlength="11">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">密码</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" name="password" placeholder="请输入密码">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">记住我</label>
                            </div>
                            <a href="/user/forgot-password" class="text-decoration-none">忘记密码？</a>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2" id="loginBtn">
                            登录
                        </button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="text-muted">还没有账号？
                            <a href="/user/register" class="text-decoration-none">立即注册</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    </main>

    <!-- 页脚 -->
    <footer class="bg-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="fw-bold text-primary"><i class="bi bi-stars"></i> AI提示词</h5>
                    <p class="text-muted small mt-2">专业的AI提示词和工作流分享平台，助力创意工作者提升效率。</p>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold">快速链接</h6>
                    <ul class="list-unstyled small">
                        <li><a href="/prompt" class="text-muted">提示词库</a></li>
                        <li><a href="/workflow" class="text-muted">工作流库</a></li>
                        <li><a href="/prompt-tips" class="text-muted">写作技巧</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold">帮助中心</h6>
                    <ul class="list-unstyled small">
                        <li><a href="#" class="text-muted">常见问题</a></li>
                        <li><a href="#" class="text-muted">联系我们</a></li>
                        <li><a href="#" class="text-muted">用户协议</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold">关注我们</h6>
                    <div class="d-flex gap-2 mt-2">
                        <a href="#" class="text-muted"><i class="bi bi-wechat fs-4"></i></a>
                        <a href="#" class="text-muted"><i class="bi bi-github fs-4"></i></a>
                        <a href="#" class="text-muted"><i class="bi bi-twitter fs-4"></i></a>
                    </div>
                </div>
            </div>
            <hr class="mt-4">
            <div class="text-center text-muted small">
                &copy; 2026 AI提示词平台. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
    <script src="/static/js/common.js"></script>
    
<script>
$(function() {
    // 切换密码可见性
    $('#togglePassword').click(function() {
        const input = $('input[name="password"]');
        const icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        }
    });
    
    // 登录表单提交
    $('#loginForm').submit(function(e) {
        e.preventDefault();
        
        const phone = $('input[name="phone"]').val();
        const password = $('input[name="password"]').val();
        
        if (!phone) {
            toastr.error('请输入手机号');
            return;
        }
        
        if (!/^1[3-9]\d{9}$/.test(phone)) {
            toastr.error('手机号格式不正确');
            return;
        }
        
        if (!password) {
            toastr.error('请输入密码');
            return;
        }
        
        const btn = $('#loginBtn');
        const originalText = btn.text();
        btn.prop('disabled', true).text('登录中...');
        
        $.post('/user/do-login', {
            phone: phone,
            password: password
        }, function(res) {
            if (res.code === 200) {
                toastr.success(res.msg);
                setTimeout(function() {
                    location.href = res.data.url || '/';
                }, 500);
            } else {
                toastr.error(res.msg);
            }
        }, 'json').always(function() {
            btn.prop('disabled', false).text(originalText);
        });
    });
});
</script>

</body>
</html>
