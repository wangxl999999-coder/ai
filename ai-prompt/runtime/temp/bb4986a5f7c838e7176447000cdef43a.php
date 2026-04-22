<?php /*a:2:{s:53:"D:\my\tare-git\ai\ai-prompt\app\view\index\index.html";i:1776826705;s:53:"D:\my\tare-git\ai\ai-prompt\app\view\layout\base.html";i:1776826641;}*/ ?>
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
                        <a class="nav-link <?php if(app('request')->controller()() == 'Index'): ?>active<?php endif; ?>" href="/">首页</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if(app('request')->controller()() == 'Prompt'): ?>active<?php endif; ?>" href="/prompt">提示词</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if(app('request')->controller()() == 'Workflow'): ?>active<?php endif; ?>" href="/workflow">工作流</a>
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
    <!-- Hero区域 -->
    <section class="py-5 text-center">
        <div class="row py-lg-5">
            <div class="col-lg-8 col-md-10 mx-auto">
                <h1 class="fw-light mb-4">
                    <span class="text-primary">AI提示词</span> 与 <span class="text-success">工作流</span> 平台
                </h1>
                <p class="lead text-muted mb-4">
                    探索优质AI提示词，提升工作效率。发布内容获得积分，用积分查看更多精彩内容。
                </p>
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    <a href="/prompt/create" class="btn btn-primary btn-lg px-4 gap-3">
                        <i class="bi bi-plus-circle"></i> 发布提示词
                    </a>
                    <a href="/workflow/create" class="btn btn-outline-secondary btn-lg px-4">
                        <i class="bi bi-diagram-3"></i> 发布工作流
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 数据统计 -->
    <section class="py-4 mb-4">
        <div class="row g-4">
            <div class="col-lg-3 col-6">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body py-4">
                        <i class="bi bi-file-text fs-1 text-primary"></i>
                        <h3 class="mt-2 mb-0">1,234</h3>
                        <p class="text-muted small mb-0">优质提示词</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body py-4">
                        <i class="bi bi-diagram-3 fs-1 text-success"></i>
                        <h3 class="mt-2 mb-0">567</h3>
                        <p class="text-muted small mb-0">高效工作流</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body py-4">
                        <i class="bi bi-people fs-1 text-warning"></i>
                        <h3 class="mt-2 mb-0">8,900+</h3>
                        <p class="text-muted small mb-0">活跃用户</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body py-4">
                        <i class="bi bi-star fs-1 text-danger"></i>
                        <h3 class="mt-2 mb-0">98%</h3>
                        <p class="text-muted small mb-0">好评率</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 推荐提示词 -->
    <?php if(!empty($recommendPrompts)): ?>
    <section class="py-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">
                <i class="bi bi-star-fill text-warning"></i> 推荐提示词
            </h3>
            <a href="/prompt" class="text-decoration-none">更多 <i class="bi bi-chevron-right"></i></a>
        </div>
        <div class="row g-4">
            <?php foreach($recommendPrompts as $item): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                <?php echo htmlentities((string) (isset($item['category']['name']) && ($item['category']['name'] !== '')?$item['category']['name']:'未分类')); ?>
                            </span>
                            <small class="text-muted"><i class="bi bi-eye"></i> <?php echo htmlentities((string) $item['view_count']); ?></small>
                        </div>
                        <h5 class="card-title"><?php echo htmlentities((string) $item['title']); ?></h5>
                        <p class="card-text text-muted small"><?php echo htmlentities((string) truncate_text($item['description'],80)); ?></p>
                        <?php if($item['tags']): ?>
                        <div class="mb-2">
                            <?php foreach($explode(',', $item['tags']) as $tag): ?>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary me-1"><?php echo htmlentities((string) $tag); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="d-flex align-items-center">
                                <img src="<?php echo htmlentities((string) $item['user']['avatar']); ?>" class="rounded-circle" width="24" height="24">
                                <span class="ms-2 text-muted small"><?php echo htmlentities((string) $item['user']['nickname']); ?></span>
                            </div>
                            <a href="/prompt/detail?id=<?php echo htmlentities((string) $item['id']); ?>" class="btn btn-sm btn-outline-primary rounded-pill">
                                查看
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- 推荐工作流 -->
    <?php if(!empty($recommendWorkflows)): ?>
    <section class="py-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">
                <i class="bi bi-diagram-3-fill text-success"></i> 推荐工作流
            </h3>
            <a href="/workflow" class="text-decoration-none">更多 <i class="bi bi-chevron-right"></i></a>
        </div>
        <div class="row g-4">
            <?php foreach($recommendWorkflows as $item): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-success bg-opacity-10 text-success">
                                <?php echo htmlentities((string) (isset($item['category']['name']) && ($item['category']['name'] !== '')?$item['category']['name']:'未分类')); ?>
                            </span>
                            <small class="text-muted"><i class="bi bi-eye"></i> <?php echo htmlentities((string) $item['view_count']); ?></small>
                        </div>
                        <h5 class="card-title"><?php echo htmlentities((string) $item['title']); ?></h5>
                        <p class="card-text text-muted small"><?php echo htmlentities((string) truncate_text($item['description'],80)); ?></p>
                        <?php if($item['tags']): ?>
                        <div class="mb-2">
                            <?php foreach($explode(',', $item['tags']) as $tag): ?>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary me-1"><?php echo htmlentities((string) $tag); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="d-flex align-items-center">
                                <img src="<?php echo htmlentities((string) $item['user']['avatar']); ?>" class="rounded-circle" width="24" height="24">
                                <span class="ms-2 text-muted small"><?php echo htmlentities((string) $item['user']['nickname']); ?></span>
                            </div>
                            <a href="/workflow/detail?id=<?php echo htmlentities((string) $item['id']); ?>" class="btn btn-sm btn-outline-success rounded-pill">
                                查看
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- 最新提示词 -->
    <?php if(!empty($latestPrompts)): ?>
    <section class="py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">
                <i class="bi bi-clock-history text-info"></i> 最新发布
            </h3>
        </div>
        <div class="row g-4">
            <?php foreach($latestPrompts as $item): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-info bg-opacity-10 text-info">
                                <?php echo htmlentities((string) (isset($item['category']['name']) && ($item['category']['name'] !== '')?$item['category']['name']:'未分类')); ?>
                            </span>
                            <small class="text-muted"><?php echo htmlentities((string) format_time($item['create_time'])); ?></small>
                        </div>
                        <h5 class="card-title"><?php echo htmlentities((string) $item['title']); ?></h5>
                        <p class="card-text text-muted small"><?php echo htmlentities((string) truncate_text($item['description'],80)); ?></p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="d-flex gap-2">
                                <span class="text-muted small"><i class="bi bi-heart"></i> <?php echo htmlentities((string) $item['like_count']); ?></span>
                                <span class="text-muted small"><i class="bi bi-chat"></i> <?php echo htmlentities((string) $item['comment_count']); ?></span>
                            </div>
                            <a href="/prompt/detail?id=<?php echo htmlentities((string) $item['id']); ?>" class="text-decoration-none">
                                查看 <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
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
    // 卡片悬停效果
    $('.hover-card').hover(
        function() { $(this).addClass('shadow'); },
        function() { $(this).removeClass('shadow'); }
    );
});
</script>

</body>
</html>
