-- =============================================
-- AI提示词平台数据库初始化脚本
-- 数据库：ai_prompt
-- 字符集：utf8mb4
-- =============================================

-- 创建数据库
CREATE DATABASE IF NOT EXISTS `ai_prompt` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE `ai_prompt`;

-- =============================================
-- 用户表
-- =============================================
CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `phone` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
    `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
    `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
    `avatar` varchar(255) NOT NULL DEFAULT '/static/images/default_avatar.png' COMMENT '头像',
    `points` int(11) NOT NULL DEFAULT 10 COMMENT '积分（注册赠送10积分）',
    `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：1正常 0禁用',
    `last_login_time` int(11) NOT NULL DEFAULT 0 COMMENT '最后登录时间',
    `last_login_ip` varchar(45) NOT NULL DEFAULT '' COMMENT '最后登录IP',
    `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
    `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `phone_unique` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户表';

-- =============================================
-- 管理员表
-- =============================================
CREATE TABLE IF NOT EXISTS `admins` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
    `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
    `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
    `avatar` varchar(255) NOT NULL DEFAULT '/static/images/default_admin_avatar.png' COMMENT '头像',
    `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：1正常 0禁用',
    `last_login_time` int(11) NOT NULL DEFAULT 0 COMMENT '最后登录时间',
    `last_login_ip` varchar(45) NOT NULL DEFAULT '' COMMENT '最后登录IP',
    `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
    `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='管理员表';

-- 插入默认管理员账号（密码：admin123456）
INSERT INTO `admins` (`username`, `password`, `nickname`, `avatar`, `status`, `last_login_time`, `last_login_ip`, `create_time`, `update_time`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '超级管理员', '/static/images/default_admin_avatar.png', 1, 0, '', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- =============================================
-- 分类表
-- =============================================
CREATE TABLE IF NOT EXISTS `categories` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL DEFAULT '' COMMENT '分类名称',
    `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型：1提示词 2工作流',
    `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
    `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：1启用 0禁用',
    `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
    `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
    PRIMARY KEY (`id`),
    KEY `type_status` (`type`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='分类表';

-- 插入默认分类
INSERT INTO `categories` (`name`, `type`, `sort`, `status`, `create_time`, `update_time`) VALUES
('通用写作', 1, 1, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('文章创作', 1, 2, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('代码生成', 1, 3, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('创意灵感', 1, 4, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('营销文案', 1, 5, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('翻译助手', 1, 6, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('教育学习', 1, 7, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('数据分析', 1, 8, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('内容创作流程', 2, 1, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('产品设计流程', 2, 2, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('软件开发流程', 2, 3, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('市场运营流程', 2, 4, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('数据分析流程', 2, 5, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('客服服务流程', 2, 6, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- =============================================
-- 提示词表
-- =============================================
CREATE TABLE IF NOT EXISTS `prompts` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '发布用户ID',
    `category_id` int(11) NOT NULL DEFAULT 0 COMMENT '分类ID',
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
    `description` text COMMENT '简介',
    `content` text NOT NULL COMMENT '提示词内容',
    `preview` text COMMENT '预览内容（部分展示）',
    `tags` varchar(255) NOT NULL DEFAULT '' COMMENT '标签，逗号分隔',
    `view_count` int(11) NOT NULL DEFAULT 0 COMMENT '查看次数',
    `like_count` int(11) NOT NULL DEFAULT 0 COMMENT '点赞数',
    `favorite_count` int(11) NOT NULL DEFAULT 0 COMMENT '收藏数',
    `comment_count` int(11) NOT NULL DEFAULT 0 COMMENT '评论数',
    `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：1待审核 2已通过 3已拒绝 0禁用',
    `is_recommend` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否推荐：1是 0否',
    `points_reward` int(11) NOT NULL DEFAULT 2 COMMENT '发布获得的积分奖励',
    `points_fee` int(11) NOT NULL DEFAULT 1 COMMENT '查看需要支付的积分',
    `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
    `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `category_id` (`category_id`),
    KEY `status` (`status`),
    KEY `is_recommend` (`is_recommend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='提示词表';

-- =============================================
-- 工作流表
-- =============================================
CREATE TABLE IF NOT EXISTS `workflows` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '发布用户ID',
    `category_id` int(11) NOT NULL DEFAULT 0 COMMENT '分类ID',
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
    `description` text COMMENT '简介',
    `content` text NOT NULL COMMENT '工作流内容',
    `preview` text COMMENT '预览内容（部分展示）',
    `tags` varchar(255) NOT NULL DEFAULT '' COMMENT '标签，逗号分隔',
    `view_count` int(11) NOT NULL DEFAULT 0 COMMENT '查看次数',
    `like_count` int(11) NOT NULL DEFAULT 0 COMMENT '点赞数',
    `favorite_count` int(11) NOT NULL DEFAULT 0 COMMENT '收藏数',
    `comment_count` int(11) NOT NULL DEFAULT 0 COMMENT '评论数',
    `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：1待审核 2已通过 3已拒绝 0禁用',
    `is_recommend` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否推荐：1是 0否',
    `points_reward` int(11) NOT NULL DEFAULT 3 COMMENT '发布获得的积分奖励',
    `points_fee` int(11) NOT NULL DEFAULT 1 COMMENT '查看需要支付的积分',
    `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
    `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `category_id` (`category_id`),
    KEY `status` (`status`),
    KEY `is_recommend` (`is_recommend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='工作流表';

-- =============================================
-- 积分记录表
-- =============================================
CREATE TABLE IF NOT EXISTS `points_records` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '用户ID',
    `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型：1收入 2支出',
    `points` int(11) NOT NULL DEFAULT 0 COMMENT '积分数量',
    `balance` int(11) NOT NULL DEFAULT 0 COMMENT '变动后余额',
    `source_type` varchar(50) NOT NULL DEFAULT '' COMMENT '来源类型',
    `source_id` int(11) NOT NULL DEFAULT 0 COMMENT '来源ID',
    `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
    `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `type` (`type`),
    KEY `source` (`source_type`, `source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='积分记录表';

-- =============================================
-- 收藏表
-- =============================================
CREATE TABLE IF NOT EXISTS `favorites` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '用户ID',
    `target_type` varchar(50) NOT NULL DEFAULT '' COMMENT '目标类型：prompt提示词、workflow工作流',
    `target_id` int(11) NOT NULL DEFAULT 0 COMMENT '目标ID',
    `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_target_unique` (`user_id`, `target_type`, `target_id`),
    KEY `target` (`target_type`, `target_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='收藏表';

-- =============================================
-- 点赞表
-- =============================================
CREATE TABLE IF NOT EXISTS `likes` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '用户ID',
    `target_type` varchar(50) NOT NULL DEFAULT '' COMMENT '目标类型',
    `target_id` int(11) NOT NULL DEFAULT 0 COMMENT '目标ID',
    `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_target_unique` (`user_id`, `target_type`, `target_id`),
    KEY `target` (`target_type`, `target_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='点赞表';

-- =============================================
-- 评论表
-- =============================================
CREATE TABLE IF NOT EXISTS `comments` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '评论用户ID',
    `target_type` varchar(50) NOT NULL DEFAULT '' COMMENT '目标类型',
    `target_id` int(11) NOT NULL DEFAULT 0 COMMENT '目标ID',
    `parent_id` int(11) NOT NULL DEFAULT 0 COMMENT '父评论ID',
    `reply_user_id` int(11) NOT NULL DEFAULT 0 COMMENT '回复用户ID',
    `content` text NOT NULL COMMENT '评论内容',
    `like_count` int(11) NOT NULL DEFAULT 0 COMMENT '点赞数',
    `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态',
    `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
    `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `target` (`target_type`, `target_id`),
    KEY `parent_id` (`parent_id`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='评论表';

-- =============================================
-- 购买记录表
-- =============================================
CREATE TABLE IF NOT EXISTS `purchases` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '用户ID',
    `target_type` varchar(50) NOT NULL DEFAULT '' COMMENT '目标类型',
    `target_id` int(11) NOT NULL DEFAULT 0 COMMENT '目标ID',
    `points` int(11) NOT NULL DEFAULT 0 COMMENT '支付的积分',
    `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_target_unique` (`user_id`, `target_type`, `target_id`),
    KEY `target` (`target_type`, `target_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='购买记录表';

-- =============================================
-- 验证码表
-- =============================================
CREATE TABLE IF NOT EXISTS `verification_codes` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `phone` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
    `code` varchar(6) NOT NULL DEFAULT '' COMMENT '验证码',
    `type` varchar(50) NOT NULL DEFAULT '' COMMENT '类型',
    `used` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否已使用',
    `expire_time` int(11) NOT NULL DEFAULT 0 COMMENT '过期时间',
    `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
    PRIMARY KEY (`id`),
    KEY `phone_type` (`phone`, `type`),
    KEY `expire_time` (`expire_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='验证码表';
