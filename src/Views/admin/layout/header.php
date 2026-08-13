<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ' . BASE_URL . '/');
    exit;
}

$currentPage = $_SERVER['REQUEST_URI'] ?? '';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'پنل مدیریت' ?> | Velora Admin</title>
    
    <!-- Bootstrap RTL -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css">
    
    <!-- Base Styles -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/base/variables.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/base/reset.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/base/typography.css">
    
    <!-- Admin Styles -->
    <style>
        :root {
            --admin-sidebar-width: 260px;
            --admin-header-height: 60px;
            --admin-primary: #4f46e5;
            --admin-primary-dark: #4338ca;
            --admin-sidebar-bg: #1e293b;
            --admin-sidebar-hover: #334155;
            --admin-content-bg: #f8fafc;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Vazirmatn', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--admin-content-bg);
            min-height: 100vh;
        }
        
        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            right: 0;
            top: 0;
            width: var(--admin-sidebar-width);
            height: 100vh;
            background: var(--admin-sidebar-bg);
            color: white;
            padding: 1.5rem 0;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .admin-sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .admin-sidebar::-webkit-scrollbar-thumb {
            background: var(--admin-sidebar-hover);
            border-radius: 3px;
        }
        
        .admin-logo {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1.5rem;
        }
        
        .admin-logo h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin: 0;
        }
        
        .admin-logo p {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.6);
            margin: 0.25rem 0 0;
        }
        
        .admin-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .admin-nav li {
            margin-bottom: 0.25rem;
        }
        
        .admin-nav a {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.2s;
            font-size: 0.95rem;
        }
        
        .admin-nav a:hover {
            background: var(--admin-sidebar-hover);
            color: white;
        }
        
        .admin-nav a.active {
            background: var(--admin-primary);
            color: white;
            font-weight: 600;
        }
        
        .admin-nav a svg {
            width: 20px;
            height: 20px;
            margin-left: 0.75rem;
            opacity: 0.8;
        }
        
        .admin-nav a.active svg {
            opacity: 1;
        }
        
        /* Main Content */
        .admin-main {
            margin-right: var(--admin-sidebar-width);
            min-height: 100vh;
        }
        
        /* Header */
        .admin-header {
            background: white;
            height: var(--admin-header-height);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .admin-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }
        
        .admin-user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .admin-user-name {
            font-weight: 600;
            color: #475569;
        }
        
        .admin-logout {
            padding: 0.5rem 1rem;
            background: #f1f5f9;
            border: none;
            border-radius: 6px;
            color: #64748b;
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        
        .admin-logout:hover {
            background: #e2e8f0;
            color: #334155;
        }
        
        /* Content */
        .admin-content {
            padding: 2rem;
        }
        
        /* Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        
        .stat-card h3 {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
        }
        
        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        
        .stat-icon.blue { background: #dbeafe; color: #2563eb; }
        .stat-icon.green { background: #d1fae5; color: #059669; }
        .stat-icon.purple { background: #ede9fe; color: #7c3aed; }
        .stat-icon.orange { background: #fed7aa; color: #ea580c; }
        
        /* Table */
        .admin-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .admin-table table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .admin-table th {
            background: #f8fafc;
            padding: 1rem;
            text-align: right;
            font-weight: 600;
            color: #475569;
            font-size: 0.875rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .admin-table td {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        
        .admin-table tr:last-child td {
            border-bottom: none;
        }
        
        .admin-table tr:hover {
            background: #f8fafc;
        }
        
        /* Badge */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-success { background: #d1fae5; color: #059669; }
        .badge-warning { background: #fef3c7; color: #d97706; }
        .badge-danger { background: #fee2e2; color: #dc2626; }
        .badge-info { background: #dbeafe; color: #2563eb; }
        
        /* Alert */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(100%);
            }
            
            .admin-main {
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="admin-logo">
            <h2>🍂 Velora</h2>
            <p>پنل مدیریت</p>
        </div>
        
        <ul class="admin-nav">
            <li>
                <a href="<?= BASE_URL ?>/admin/dashboard" class="<?= str_contains($currentPage, '/admin/dashboard') || str_contains($currentPage, '/admin') && !str_contains($currentPage, '/admin/') ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    داشبورد
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/admin/products" class="<?= str_contains($currentPage, '/admin/products') ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    محصولات
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/admin/orders" class="<?= str_contains($currentPage, '/admin/orders') ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    سفارشات
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/admin/users" class="<?= str_contains($currentPage, '/admin/users') ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    کاربران
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/admin/reviews" class="<?= str_contains($currentPage, '/admin/reviews') ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                    نظرات
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/admin/theme-settings" class="<?= str_contains($currentPage, '/admin/theme-settings') ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    تنظیمات تم
                </a>
            </li>
        </ul>
    </aside>
    
    <!-- Main Content -->
    <div class="admin-main">
        <!-- Header -->
        <header class="admin-header">
            <h1><?= $pageTitle ?? 'پنل مدیریت' ?></h1>
            <div class="admin-user-info">
                <span class="admin-user-name"><?= Security::e($_SESSION['username'] ?? 'ادمین') ?></span>
                <a href="<?= BASE_URL ?>/" class="admin-logout">بازگشت به سایت</a>
                <a href="<?= BASE_URL ?>/logout" class="admin-logout">خروج</a>
            </div>
        </header>
        
        <!-- Content -->
        <div class="admin-content">
