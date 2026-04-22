// 公共JavaScript

// Toastr配置（如果使用toastr库）
if (typeof toastr !== 'undefined') {
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 3000,
        extendedTimeOut: 1000,
        showEasing: 'swing',
        hideEasing: 'linear',
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut'
    };
}

// 如果没有toastr，模拟一个简单的提示
window.toastr = window.toastr || {
    success: function(msg) {
        showToast(msg, 'success');
    },
    error: function(msg) {
        showToast(msg, 'error');
    },
    info: function(msg) {
        showToast(msg, 'info');
    },
    warning: function(msg) {
        showToast(msg, 'warning');
    }
};

function showToast(msg, type) {
    // 创建toast容器
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;';
        document.body.appendChild(container);
    }
    
    // 创建toast元素
    const toast = document.createElement('div');
    const bgColors = {
        success: '#198754',
        error: '#dc3545',
        info: '#0dcaf0',
        warning: '#ffc107'
    };
    
    toast.style.cssText = `
        background: ${bgColors[type] || '#0d6efd'};
        color: white;
        padding: 12px 24px;
        margin-bottom: 10px;
        border-radius: 4px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideIn 0.3s ease;
    `;
    toast.textContent = msg;
    
    container.appendChild(toast);
    
    // 动画
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    if (!document.getElementById('toast-style')) {
        style.id = 'toast-style';
        document.head.appendChild(style);
    }
    
    // 自动消失
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// 确认对话框
window.confirm = window.confirm || function(msg, callback) {
    return new Promise((resolve) => {
        const result = window.originalConfirm ? window.originalConfirm(msg) : confirm(msg);
        if (callback) callback(result);
        resolve(result);
    });
};

// 格式化数字
function formatNumber(num) {
    if (num >= 10000) {
        return (num / 10000).toFixed(1) + 'w';
    } else if (num >= 1000) {
        return (num / 1000).toFixed(1) + 'k';
    }
    return num.toString();
}

// 防抖函数
function debounce(fn, delay) {
    let timer = null;
    return function() {
        const context = this;
        const args = arguments;
        if (timer) clearTimeout(timer);
        timer = setTimeout(() => {
            fn.apply(context, args);
        }, delay);
    };
}

// 节流函数
function throttle(fn, delay) {
    let lastTime = 0;
    return function() {
        const now = Date.now();
        if (now - lastTime >= delay) {
            fn.apply(this, arguments);
            lastTime = now;
        }
    };
}

// 图片懒加载
function lazyLoad() {
    const images = document.querySelectorAll('img[data-src]');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => observer.observe(img));
}

// 复制到剪贴板
function copyToClipboard(text) {
    return new Promise((resolve, reject) => {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(resolve).catch(reject);
        } else {
            // 降级方案
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            try {
                document.execCommand('copy');
                resolve();
            } catch (err) {
                reject(err);
            } finally {
                document.body.removeChild(textarea);
            }
        }
    });
}

// 获取URL参数
function getQueryParam(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

// 页面加载完成后执行
document.addEventListener('DOMContentLoaded', function() {
    // 初始化懒加载
    lazyLoad();
    
    // 初始化Bootstrap工具提示
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }
    
    // 图片错误处理
    document.querySelectorAll('img').forEach(img => {
        img.addEventListener('error', function() {
            this.src = '/static/images/default_avatar.png';
        });
    });
    
    // 表单自动聚焦
    const firstInput = document.querySelector('form input:not([type="hidden"]):not([type="submit"])');
    if (firstInput) {
        firstInput.focus();
    }
});

// 全局AJAX错误处理
$(document).ajaxError(function(event, xhr, settings, thrownError) {
    console.error('AJAX Error:', settings.url, thrownError);
    if (xhr.status === 401) {
        // 未授权，跳转到登录页
        location.href = '/user/login';
    } else if (xhr.status === 403) {
        toastr.error('没有权限执行此操作');
    } else if (xhr.status === 500) {
        toastr.error('服务器错误，请稍后重试');
    }
});
