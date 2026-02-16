<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelas - Portal Guru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            border-right: 1px solid rgba(75, 139, 244, 0.1);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Hamburger Menu Button */
        .hamburger-btn {
            display: none;
            width: 48px;
            height: 48px;
            background: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .hamburger-btn:hover {
            background: #f8fafc;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .hamburger-btn span {
            width: 24px;
            height: 3px;
            background: #667eea;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .hamburger-btn.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger-btn.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger-btn.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -7px);
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
        }

        /* Bottom Navigation for Mobile */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 100;
            padding: 0.75rem;
            border-top: 1px solid #e5e7eb;
        }

        .mobile-bottom-nav .hamburger-btn {
            display: flex;
            margin: 0 auto;
            width: 56px;
            height: 56px;
        }

        .sidebar-brand {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .brand-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }

        .brand-icon:hover {
            transform: scale(1.05) rotate(5deg);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-weight: 700;
            font-size: 1.25rem;
            color: #1e293b;
        }

        .brand-subtitle {
            font-size: 0.7rem;
            color: #4B8BF4;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
        }

        .nav-item {
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 3px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.1) 0%, transparent 100%);
            transition: width 0.3s ease;
        }

        .nav-item:hover::before {
            width: 100%;
        }

        .nav-item:hover {
            background: linear-gradient(90deg, #f8fafc 0%, #ffffff 100%);
            color: #1e293b;
            transform: translateX(5px);
        }

        .nav-item.active {
            background: linear-gradient(90deg, #EBF3FF 0%, #f0f7ff 100%);
            color: #667eea;
            border-left-color: #667eea;
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #f0f0f0;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            min-width: 40px;
            min-height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            flex-shrink: 0;
            aspect-ratio: 1;
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #1e293b;
        }

        .user-email {
            font-size: 0.75rem;
            color: #64748b;
        }

        .btn-logout {
            width: 100%;
            margin-top: 0.75rem;
            padding: 0.5rem 1rem;
            background: #fee2e2;
            color: #dc2626;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-logout:hover {
            background: #fecaca;
            color: #b91c1c;
        }

        /* Bottom Navigation (Mobile Only) */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 100;
            padding: 0.5rem 0;
            border-top: 1px solid #e5e7eb;
            justify-content: space-around;
            align-items: center;
        }

        .bottom-nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
            text-decoration: none;
            color: #64748b;
            font-size: 0.75rem;
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
            gap: 0.25rem;
        }

        .bottom-nav-item:hover {
            background: #f8fafc;
        }

        .bottom-nav-item.active {
            color: #4B8BF4;
        }

        .bottom-nav-item.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 3px;
            background: #4B8BF4;
            border-radius: 0 0 3px 3px;
        }

        .bottom-nav-icon {
            font-size: 1.25rem;
            margin-bottom: 2px;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 1.5rem 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .search-box {
            position: relative;
            width: 240px;
            max-width: 240px;
            flex-shrink: 1;
        }

        .search-box input {
            border: none;
            outline: none;
            width: 100%;
            margin-left: 0.5rem;
        }

        /* Header Layout */
        .header-left {
            display: flex;
            align-items: center;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* User Profile in Header */
        .user-profile-header {
            display: none; /* Hidden on desktop */
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar-header {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            text-transform: uppercase;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .user-info-header {
            display: flex;
            flex-direction: column;
        }

        .user-name-header {
            font-weight: 600;
            font-size: 0.95rem;
            color: #1e293b;
        }

        .user-email-header {
            font-size: 0.8rem;
            color: #64748b;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .header-btn:hover {
            background: #f1f5f9;
        }

        .page-title-section {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            color: #64748b;
        }

        /* Class Cards */
        .class-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .class-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .class-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .class-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .class-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .class-1 {
            background: #fee2e2;
        }

        .class-2 {
            background: #fef3c7;
        }

        .class-3 {
            background: #d1fae5;
        }

        .class-4 {
            background: #dbeafe;
        }

        .class-5 {
            background: #e0e7ff;
        }

        .class-6 {
            background: #fce7f3;
        }

        .class-all {
            background: #f3f4f6;
        }

        .class-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
        }

        .class-count {
            font-size: 0.85rem;
            color: #64748b;
        }

        .class-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .class-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-view-games {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.6rem 1.25rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-view-games:hover {
            color: white;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        }

        /* Game List in Class */
        .games-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }

        .game-item {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.2s ease;
            text-decoration: none;
            color: inherit;
        }

        .game-item:hover {
            background: #EBF3FF;
            transform: translateY(-2px);
        }

        .game-thumb {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .game-info {
            flex: 1;
            min-width: 0;
        }

        .game-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .game-meta {
            font-size: 0.8rem;
            color: #64748b;
        }

        .game-status {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-weight: 600;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .empty-class {
            text-align: center;
            padding: 2rem;
            color: #94a3b8;
        }

        .empty-class-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .btn-create {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.6rem 1.25rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-create:hover {
            color: white;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        }

        /* Bottom Navigation Bar (Mobile Only) */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 8px 0;
            border-top: 1px solid #f0f0f0;
        }

        .bottom-nav {
            display: none;
            grid-template-columns: repeat(4, 1fr);
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 8px 4px;
            text-decoration: none;
            color: #64748b;
            transition: all 0.2s ease;
            min-height: 64px;
            position: relative;
        }

        .bottom-nav-item:hover {
            background: #f8fafc;
        }

        .bottom-nav-item.active {
            color: #4B8BF4;
        }

        .bottom-nav-item.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 3px;
            background: #4B8BF4;
            border-radius: 0 0 3px 3px;
        }

        .hamburger-bottom-btn {
            background: none;
            border: none;
            cursor: pointer;
        }

        .hamburger-bottom-btn .bottom-nav-icon {
            font-size: 1.5rem;
        }

        .bottom-nav-icon {
            font-size: 24px;
            margin-bottom: 4px;
        }

        .bottom-nav-label {
            font-size: 11px;
            font-weight: 600;
            text-align: center;
        }


        @media (max-width: 768px) {
            /* Hide sidebar completely on mobile */
            .sidebar {
                display: none !important;
            }

            /* Hide sidebar overlay on mobile */
            .sidebar-overlay {
                display: none !important;
            }

            /* Show bottom navigation */
            .bottom-nav {
                display: flex;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 100;
                background: white;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
                padding: 0.5rem 0;
                border-top: 1px solid #e5e7eb;
                justify-content: space-around;
                align-items: center;
            }

            /* Adjust main content for mobile */
            .main-content {
                margin-left: 0;
                padding: 1rem;
                padding-bottom: 80px; /* Space for bottom nav */
            }

            body {
                flex-direction: column;
            }

            .header {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                flex-wrap: nowrap;
                padding: 1rem;
                background: white;
                border-radius: 16px;
                margin-bottom: 1rem;
            }

            .user-info-header {
                display: none; /* Hide email on very small screens */
            }

            .user-name-header {
                font-size: 0.85rem;
            }

            .user-avatar-header {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            /* Hide sidebar completely on mobile */
            .sidebar {
                display: none !important;
            }

            /* Hide sidebar overlay on mobile */
            .sidebar-overlay {
                display: none !important;
            }

            /* Show bottom navigation */
            .bottom-nav {
                display: flex;
            }

            /* Adjust main content for mobile */
            .main-content {
                margin-left: 0;
                padding: 1rem;
                padding-bottom: 80px; /* Space for bottom nav */
            }

            body {
                flex-direction: column;
            }

            .header {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                flex-wrap: nowrap;
                padding: 1rem;
                background: white;
                border-radius: 16px;
                margin-bottom: 1rem;
            }

            .user-info-header {
                display: none; /* Hide email on very small screens */
            }

            .user-name-header {
                font-size: 0.85rem;
            }

            .user-avatar-header {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            /* Show user profile in header on mobile */
            .user-profile-header {
                display: flex;
            }

            .search-box {
                flex: 0 0 auto;
                min-width: 0;
                position: relative;
                z-index: 10;
                max-width: 240px;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: flex-end;
            }

            /* Collapsible search for mobile */
            .search-box.collapsed {
                width: 40px;
                max-width: 40px;
                background: white;
                border-radius: 50%;
                box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            }

            .search-box.collapsed input {
                opacity: 0;
                width: 0;
                padding: 0;
                margin: 0;
            }

            .search-box.collapsed .search-icon {
                cursor: pointer;
                font-size: 1.5rem;
                padding: 0.5rem;
            }

            .header-actions {
                justify-content: center;
            }

            .games-grid {
                grid-template-columns: 1fr;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .class-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .class-actions {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>



    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">📚</div>
            <div class="brand-text">
                <div class="brand-name">EduPlay</div>
                <div class="brand-subtitle">Portal Guru</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('teacher.dashboard') }}" class="nav-item">
                <span class="nav-icon">📊</span>
                <span>Dasbor</span>
            </a>
            <a href="{{ route('teacher.classes') }}" class="nav-item active">
                <span class="nav-icon">👥</span>
                <span>Kelas</span>
            </a>
            <a href="{{ route('teacher.games') }}" class="nav-item">
                <span class="nav-icon">🎮</span>
                <span>Game</span>
            </a>
            <a href="{{ route('teacher.schedules') }}" class="nav-item">
                <span class="nav-icon">📈</span>
                <span>Jadwal</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">{{ substr($teacher->name, 0, 1) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ $teacher->name }}</div>
                    <div class="user-email">{{ $teacher->email ?? 'guru@sekolah.edu' }}</div>
                </div>
            </div>
            <a href="{{ route('teacher.logout') }}" class="btn-logout" onclick="confirmLogout(event)">
                🚪 Keluar
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <div class="user-profile-header">
                    <div class="user-avatar-header">{{ substr($teacher->name, 0, 1) }}</div>
                    <div class="user-info-header">
                        <div class="user-name-header">{{ $teacher->name }}</div>
                        <div class="user-email-header">{{ $teacher->email ?? 'guru@sekolah.edu' }}</div>
                    </div>
                    <a href="{{ route('teacher.logout') }}" onclick="confirmLogout(event)" style="margin-left: 10px; text-decoration: none; font-size: 1.2rem; background: #fee2e2; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                        🚪
                    </a>
                </div>
            </div>
            <div class="header-right">
                <div class="search-box">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInput" placeholder="Cari game...">
                </div>
            </div>
        </div>

        <!-- Page Title -->
        <div class="page-title-section">
            <h1 class="page-title">👥 Kelas Saya</h1>
            <p class="page-subtitle">Lihat game yang Anda buat berdasarkan tingkat kelas</p>
        </div>

        @php
            $classColors = [
                1 => '#ef4444',
                2 => '#f59e0b',
                3 => '#10b981',
                4 => '#3b82f6',
                5 => '#6366f1',
                6 => '#ec4899'
            ];
            $classEmojis = [
                1 => '🔴',
                2 => '🟡',
                3 => '🟢',
                4 => '🔵',
                5 => '🟣',
                6 => '💗'
            ];
        @endphp

        <!-- Games for All Classes -->
        @if($gamesAllClasses->count() > 0)
            <div class="class-card">
                <div class="class-header">
                    <div class="class-title">
                        <div class="class-icon class-all">📚</div>
                        <div>
                            <div class="class-name">Semua Kelas</div>
                            <div class="class-count">Game tersedia untuk semua siswa</div>
                        </div>
                    </div>
                    <div class="class-actions">
                        <span class="class-badge">{{ $gamesAllClasses->count() }} Game</span>
                        <a href="{{ route('teacher.games') }}" class="btn-view-games">👁️ Lihat Game</a>
                    </div>
                </div>
                <div class="games-grid">
                    @foreach($gamesAllClasses as $game)
                        <a href="{{ route('teacher.games.edit', $game->id) }}" class="game-item">
                            <div class="game-thumb" style="background: linear-gradient(135deg, #f3f4f6, #e5e7eb);">
                                {{ $game->template->icon ?? '🎮' }}
                            </div>
                            <div class="game-info">
                                <div class="game-title">{{ $game->title }}</div>
                                <div class="game-meta">{{ $game->category ?? 'Tanpa Kategori' }} ·
                                    {{ $game->questions->count() }} Soal
                                </div>
                            </div>
                            <span class="game-status {{ $game->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $game->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Classes 1-6 -->
        @foreach($gamesByClass as $classNum => $games)
            <div class="class-card">
                <div class="class-header">
                    <div class="class-title">
                        <div class="class-icon class-{{ $classNum }}">{{ $classEmojis[$classNum] }}</div>
                        <div>
                            <div class="class-name">Kelas {{ $classNum }}</div>
                            <div class="class-count">Game untuk siswa kelas {{ $classNum }}</div>
                        </div>
                    </div>
                    <div class="class-actions">
                        <span class="class-badge">{{ $games->count() }} Game</span>
                        <a href="{{ route('teacher.games', ['class' => $classNum]) }}" class="btn-view-games">👁️ Lihat
                            Game</a>
                    </div>
                </div>

                @if($games->count() > 0)
                    <div class="games-grid">
                        @foreach($games as $game)
                            <a href="{{ route('teacher.games.edit', $game->id) }}" class="game-item">
                                <div class="game-thumb"
                                    style="background: linear-gradient(135deg, {{ $classColors[$classNum] }}22, {{ $classColors[$classNum] }}44);">
                                    {{ $game->template->icon ?? '🎮' }}
                                </div>
                                <div class="game-info">
                                    <div class="game-title">{{ $game->title }}</div>
                                    <div class="game-meta">{{ $game->category ?? 'Tanpa Kategori' }} ·
                                        {{ $game->questions->count() }} Soal
                                    </div>
                                </div>
                                <span class="game-status {{ $game->is_active ? 'status-active' : 'status-inactive' }}">
                                    {{ $game->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="empty-class">
                        <div class="empty-class-icon">📭</div>
                        <p>Belum ada game untuk kelas {{ $classNum }}</p>
                        <a href="{{ route('teacher.games.create') }}" class="btn-create" style="margin-top: 0.5rem;">
                            ➕ Buat Game Pertama
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bottom Navigation (Mobile Only) -->
    <nav class="bottom-nav">
        <a href="{{ route('teacher.dashboard') }}" class="bottom-nav-item">
            <span class="bottom-nav-icon">📊</span>
            <span class="bottom-nav-label">Dasbor</span>
        </a>
        <a href="{{ route('teacher.classes') }}" class="bottom-nav-item active">
            <span class="bottom-nav-icon">👥</span>
            <span class="bottom-nav-label">Kelas</span>
        </a>
        <a href="{{ route('teacher.games') }}" class="bottom-nav-item">
            <span class="bottom-nav-icon">🎮</span>
            <span class="bottom-nav-label">Game</span>
        </a>
        <a href="{{ route('teacher.schedules') }}" class="bottom-nav-item">
            <span class="bottom-nav-icon">📈</span>
            <span class="bottom-nav-label">Jadwal</span>
        </a>
    </nav>

    <script>
        // Collapsible Search Box for Mobile
        const searchBox = document.querySelector('.search-box');
        const searchIcon = document.querySelector('.search-icon');
        const searchInput = document.querySelector('.search-box input');

        // Initialize collapsed state on mobile
        if (window.innerWidth <= 768 && searchBox) {
            searchBox.classList.add('collapsed');
        }

        // Toggle search box on icon click
        if (searchIcon && searchBox) {
            searchIcon.addEventListener('click', (e) => {
                if (window.innerWidth <= 768) {
                    e.stopPropagation();
                    searchBox.classList.toggle('collapsed');
                    
                    // Focus input when expanded
                    if (!searchBox.classList.contains('collapsed')) {
                        setTimeout(() => searchInput.focus(), 300);
                    }
                }
            });
        }

        // Auto-collapse when input loses focus
        if (searchInput && searchBox) {
            searchInput.addEventListener('blur', () => {
                if (window.innerWidth <= 768) {
                    setTimeout(() => {
                        searchBox.classList.add('collapsed');
                    }, 200);
                }
            });
        }

        // Collapse search when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768 && searchBox && !searchBox.contains(e.target)) {
                searchBox.classList.add('collapsed');
            }
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768 && searchBox) {
                searchBox.classList.remove('collapsed');
            } else if (window.innerWidth <= 768 && searchBox) {
                searchBox.classList.add('collapsed');
            }
        });

        // Search functionality
        const searchInputEl = document.getElementById('searchInput');
        if (searchInputEl) {
            searchInputEl.addEventListener('input', function (e) {
                const query = e.target.value.toLowerCase();
                const gameItems = document.querySelectorAll('.game-item');
                gameItems.forEach(item => {
                    const title = item.querySelector('.game-title')?.textContent.toLowerCase() || '';
                    const meta = item.querySelector('.game-meta')?.textContent.toLowerCase() || '';
                    item.style.display = (title.includes(query) || meta.includes(query)) ? 'flex' : 'none';
                });
            });
        }

        // Hamburger Menu Toggle
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        const toggleSidebar = () => {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
            hamburgerBtn.classList.toggle('active');
            
            // Prevent body scroll when sidebar is open
            if (sidebar.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        };

        // Toggle sidebar when hamburger button is clicked
        if (hamburgerBtn) {
            hamburgerBtn.addEventListener('click', toggleSidebar);
        }

        // Close sidebar when overlay is clicked
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', toggleSidebar);
        }

        // Close sidebar when navigation link is clicked (mobile)
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', () => {
                if (window.innerWidth <= 768 && sidebar.classList.contains('active')) {
                    toggleSidebar();
                }
            });
        });

        // Close sidebar on window resize if it's open and screen becomes larger
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768 && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                hamburgerBtn.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        // Prevent search box from triggering sidebar
        const searchBox = document.querySelector('.search-box');
        const searchInput = document.querySelector('.search-box input');
        
        if (searchBox) {
            searchBox.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        }
        
        if (searchInput) {
            searchInput.addEventListener('click', (e) => {
                e.stopPropagation();
            });
            searchInput.addEventListener('focus', (e) => {
                e.stopPropagation();
            });
        }
    </script>
    <script>
        function confirmLogout(event) {
            event.preventDefault();
            const logoutUrl = event.currentTarget.getAttribute('href');

            Swal.fire({
                title: 'Yakin ingin keluar?',
                text: "Anda harus login kembali untuk mengakses halaman ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = logoutUrl;

                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = "{{ csrf_token() }}";
                    form.appendChild(tokenInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</body>

</html>
