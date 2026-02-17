<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor - Portal Guru</title>
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

        body.dark-mode {
            background: #0f172a;
            color: #e2e8f0;
        }

        body.dark-mode .sidebar {
            background: #0b1220;
            box-shadow: none;
        }

        body.dark-mode .sidebar-brand,
        body.dark-mode .sidebar-footer {
            border-color: #1f2937;
        }

        body.dark-mode .brand-name,
        body.dark-mode .user-name {
            color: #e2e8f0;
        }

        body.dark-mode .brand-subtitle {
            color: #93c5fd;
        }

        body.dark-mode .nav-item {
            color: #cbd5f5;
        }

        body.dark-mode .nav-item:hover {
            background: #1f2937;
            color: #ffffff;
        }

        body.dark-mode .nav-item.active {
            background: #1f2937;
            color: #60a5fa;
            border-left-color: #60a5fa;
        }

        body.dark-mode .user-email {
            color: #94a3b8;
        }

        body.dark-mode .main-content {
            background: radial-gradient(circle at 15% -10%, #1e293b 0%, #0f172a 45%, #020617 100%);
        }

        body.dark-mode .dashboard-header {
            background: #111827 !important;
            border: 1px solid #1f2937;
        }

        body.dark-mode .user-name-header {
            color: #e2e8f0;
        }

        body.dark-mode .user-email-header {
            color: #94a3b8;
        }

        body.dark-mode .search-box input {
            background: #0b1220;
            border-color: #1f2937;
            color: #e2e8f0;
        }

        body.dark-mode .search-box input::placeholder {
            color: #64748b;
        }

        body.dark-mode .search-box .search-icon {
            color: #cbd5e1;
        }

        body.dark-mode .search-box input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }

        body.dark-mode .header-btn {
            background: #0b1220;
            color: #e2e8f0;
        }

        body.dark-mode .header-btn:hover {
            background: #1f2937;
            color: #ffffff;
        }

        body.dark-mode .welcome-card {
            background: linear-gradient(135deg, #334155 0%, #1e293b 55%, #3730a3 100%);
            box-shadow: 0 14px 40px rgba(2, 6, 23, 0.55);
        }

        body.dark-mode .stat-card,
        body.dark-mode .section-card {
            background: #111827;
            box-shadow: none;
        }

        body.dark-mode .section-card {
            border-color: #1f2937;
        }

        body.dark-mode .stat-card.students {
            background: linear-gradient(135deg, #0f172a 0%, #111827 100%);
            border-left-color: #60a5fa;
        }

        body.dark-mode .stat-card.games {
            background: linear-gradient(135deg, #0f172a 0%, #111827 100%);
            border-left-color: #a78bfa;
        }

        body.dark-mode .stat-card.score {
            background: linear-gradient(135deg, #0f172a 0%, #111827 100%);
            border-left-color: #fbbf24;
        }

        body.dark-mode .stat-card.accuracy {
            background: linear-gradient(135deg, #0f172a 0%, #111827 100%);
            border-left-color: #34d399;
        }

        body.dark-mode .stat-value,
        body.dark-mode .section-title,
        body.dark-mode .performer-name {
            color: #e2e8f0;
        }

        body.dark-mode .stat-value,
        body.dark-mode .performer-score {
            background: none;
            -webkit-text-fill-color: #e2e8f0;
            color: #e2e8f0;
        }

        body.dark-mode .performer-rank {
            background: none;
            -webkit-text-fill-color: #93c5fd;
            color: #93c5fd;
        }

        body.dark-mode .stat-label,
        body.dark-mode .performer-stats,
        body.dark-mode .empty-state {
            color: #94a3b8;
        }

        body.dark-mode .btn-filter {
            background: #0b1220;
            border-color: #1f2937;
            color: #cbd5f5;
        }

        body.dark-mode .btn-filter:hover {
            border-color: #60a5fa;
            color: #60a5fa;
        }

        body.dark-mode .btn-filter.active {
            background: #1d4ed8;
            border-color: #1d4ed8;
            color: #ffffff;
        }

        body.dark-mode .top-performer-item {
            background: #0b1220;
        }

        body.dark-mode .top-performer-item:hover {
            background: #1f2937;
        }

        body.dark-mode thead {
            background: #1d4ed8;
        }

        body.dark-mode tbody td {
            border-bottom-color: #1f2937;
        }

        body.dark-mode tbody tr:hover {
            background: #1f2937;
        }

        body.dark-mode .progress-bar-custom {
            background: #1f2937;
        }

        body.dark-mode .table-responsive {
            border: 1px solid #1f2937;
        }

        body.dark-mode .bottom-nav {
            background: #0b1220;
            border-top-color: #1f2937;
            box-shadow: 0 -2px 12px rgba(2, 6, 23, 0.65);
        }

        body.dark-mode .bottom-nav-item {
            color: #94a3b8;
        }

        body.dark-mode .bottom-nav-item:hover {
            background: #111827;
        }

        body.dark-mode .bottom-nav-item.active {
            color: #60a5fa;
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
            flex-shrink: 0;
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
        }

        .bottom-nav {
            display: none;
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
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
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
            font-size: 12px;
            font-weight: 500;
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
            overflow-y: auto;
            overflow-x: hidden;
        }

        .nav-item {
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #475569;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
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
            z-index: 0;
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

        .nav-icon {
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
        }

        .nav-item span:not(.nav-icon) {
            position: relative;
            z-index: 1;
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
            margin-bottom: 2rem;
        }

        .search-box {
            position: relative;
            width: 240px;
            max-width: 240px;
            flex-shrink: 1;
        }

        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9rem;
            background: white;
            transition: all 0.2s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: #4B8BF4;
            box-shadow: 0 0 0 3px rgba(75, 139, 244, 0.1);
        }

        .search-box .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            user-select: none;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-shrink: 0;
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
            color: #1e293b;
        }

        .dashboard-search-box {
            margin-left: auto;
        }

        .dashboard-theme-toggle {
            flex-shrink: 0;
        }

        /* Welcome Card */
        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2.5rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.4);
            position: relative;
            overflow: hidden;
        }

        .welcome-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .welcome-card::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .welcome-card h1 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .welcome-card p {
            font-size: 1.05rem;
            opacity: 0.95;
            position: relative;
            z-index: 1;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.75rem;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 5px solid;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(0, 0, 0, 0.03) 0%, transparent 70%);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-card.students {
            border-color: #667eea;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        }

        .stat-card.games {
            border-color: #f093fb;
            background: linear-gradient(135deg, #ffffff 0%, #fef5ff 100%);
        }

        .stat-card.score {
            border-color: #fbbf24;
            background: linear-gradient(135deg, #ffffff 0%, #fffbf0 100%);
        }

        .stat-card.accuracy {
            border-color: #22c55e;
            background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.2) rotate(10deg);
        }

        .stat-value {
            font-size: 2.25rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1e293b 0%, #475569 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Section Card */
        .section-card {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .section-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Filter Buttons */
        .filter-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .btn-filter {
            padding: 0.6rem 1.25rem;
            border: 2px solid #e2e8f0;
            background: white;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            text-decoration: none;
            color: #64748b;
            position: relative;
            overflow: hidden;
        }

        .btn-filter::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(102, 126, 234, 0.1);
            transform: translate(-50%, -50%);
            transition: width 0.4s ease, height 0.4s ease;
        }

        .btn-filter:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-filter:hover {
            border-color: #667eea;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        }

        .btn-filter.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        /* Top Performer */
        .top-performer-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            border-radius: 15px;
            margin-bottom: 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .top-performer-item:hover {
            background: linear-gradient(135deg, #EBF3FF 0%, #f0f7ff 100%);
            transform: translateX(8px) scale(1.02);
            border-color: #667eea;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.15);
        }

        .performer-rank {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-right: 1rem;
            min-width: 40px;
        }

        .performer-info {
            flex: 1;
        }

        .performer-name {
            font-weight: 600;
            color: #1e293b;
        }

        .performer-stats {
            font-size: 0.85rem;
            color: #64748b;
        }

        .performer-score {
            font-size: 1.4rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Table */
        .table-responsive {
            border-radius: 12px;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            margin-bottom: 0;
        }

        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        thead th {
            padding: 1rem;
            font-weight: 600;
            border: none;
        }

        tbody td {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        .table-students th,
        .table-students td,
        .table-activities th,
        .table-activities td {
            white-space: nowrap;
        }

        .table-students td:nth-child(2),
        .table-activities td:nth-child(2) {
            white-space: normal;
            overflow-wrap: anywhere;
        }

        .badge-class {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.4rem 0.9rem;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
            font-weight: 600;
            font-size: 0.8rem;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .progress-bar-custom {
            height: 6px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 0.4rem;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%);
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #94a3b8;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        /* Responsive */
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
                z-index: 9999;
                background: white;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
                padding: 0.5rem 0;
                border-top: 1px solid #e5e7eb;
                justify-content: space-around;
                align-items: center;
                width: 100%;
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

            .header.dashboard-header {
                display: grid !important;
                grid-template-columns: minmax(0, 1fr) auto;
                grid-template-areas:
                    "left theme"
                    "search search";
                align-items: center;
                column-gap: 0.75rem;
                row-gap: 0.75rem;
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

            .header.dashboard-header .dashboard-header-left {
                grid-area: left;
                width: auto !important;
                min-width: 0;
                display: flex !important;
                align-items: center;
                flex-wrap: nowrap !important;
                gap: 0.75rem !important;
                justify-content: flex-start !important;
            }

            .header.dashboard-header .dashboard-theme-toggle {
                grid-area: theme;
                justify-self: end;
            }

            .header.dashboard-header .dashboard-search-box {
                grid-area: search;
                width: 100% !important;
                max-width: none !important;
                margin-left: 0 !important;
                display: block;
                min-width: 0;
                position: relative;
                z-index: 10;
            }

            .header.dashboard-header .dashboard-search-box input {
                opacity: 1 !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0.75rem 1rem 0.75rem 2.75rem !important;
                pointer-events: auto;
                cursor: text;
                z-index: 11;
                position: relative;
            }

            .header.dashboard-header .dashboard-search-box .search-icon {
                cursor: pointer;
            }

            .welcome-card {
                padding: 1.5rem;
            }

            .welcome-card h1 {
                font-size: 1.5rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .table-students {
                min-width: 760px !important;
            }

            .table-activities {
                min-width: 720px !important;
            }

            .table-students th:nth-child(1),
            .table-students td:nth-child(1) {
                width: 56px;
            }

            .table-students th:nth-child(2),
            .table-students td:nth-child(2) {
                min-width: 170px;
            }

            .table-students th:nth-child(3),
            .table-students td:nth-child(3) {
                min-width: 120px;
            }

            .table-students th:nth-child(4),
            .table-students td:nth-child(4) {
                min-width: 130px;
            }

            .table-students th:nth-child(5),
            .table-students td:nth-child(5) {
                min-width: 130px;
            }

            .table-students th:nth-child(6),
            .table-students td:nth-child(6) {
                min-width: 150px;
            }

            .table-activities th:nth-child(1),
            .table-activities td:nth-child(1) {
                min-width: 120px;
            }

            .table-activities th:nth-child(2),
            .table-activities td:nth-child(2) {
                min-width: 220px;
            }

            .table-activities th:nth-child(3),
            .table-activities td:nth-child(3) {
                min-width: 90px;
            }

            .table-activities th:nth-child(4),
            .table-activities td:nth-child(4) {
                min-width: 110px;
            }

            .table-activities th:nth-child(5),
            .table-activities td:nth-child(5) {
                min-width: 120px;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/mobile-responsive-fix.css') }}">
</head>

<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Bottom Navigation (Mobile Only) -->
    <nav class="bottom-nav">
        <a href="{{ route('teacher.dashboard') }}" class="bottom-nav-item active">
            <span class="bottom-nav-icon">📊</span>
            <span class="bottom-nav-label">Dasbor</span>
        </a>
        <a href="{{ route('teacher.classes') }}" class="bottom-nav-item">
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
            <a href="{{ route('teacher.dashboard') }}" class="nav-item active">
                <span class="nav-icon">📊</span>
                <span>Dasbor</span>
            </a>
            <a href="{{ route('teacher.classes') }}" class="nav-item">
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
        <div class="header dashboard-header">
            <div class="header-left dashboard-header-left">
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
            <div class="search-box dashboard-search-box">
                <span class="search-icon">🔍</span>
                <input type="text" id="searchInput" placeholder="Cari siswa, game, atau skor...">
            </div>
            <button class="header-btn theme-toggle dashboard-theme-toggle" type="button" aria-label="Ganti tema">🌙</button>
        </div>

        <!-- Welcome Card -->
        <div class="welcome-card">
            <h1>Selamat datang kembali, {{ $teacher->name }}! 👋</h1>
            <p>{{ $teacher->subject ? 'Guru ' . $teacher->subject : 'Guru' }} | Pantau perkembangan siswa Anda
                here</p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card students">
                <div class="stat-icon">👥</div>
                <div class="stat-value">{{ $totalStudents }}</div>
                <div class="stat-label">Total Siswa</div>
            </div>

            <div class="stat-card games">
                <div class="stat-icon">🎮</div>
                <div class="stat-value">{{ $totalGamesPlayed }}</div>
                <div class="stat-label">Game Dimainkan</div>
            </div>

            <div class="stat-card score">
                <div class="stat-icon">⭐</div>
                <div class="stat-value">{{ $averageScore }}</div>
                <div class="stat-label">Rata-rata Skor</div>
            </div>

            <div class="stat-card accuracy">
                <div class="stat-icon">🎯</div>
                <div class="stat-value">{{ $overallAccuracy }}%</div>
                <div class="stat-label">Overall Accuracy</div>
            </div>
        </div>

        <!-- Class Distribution -->
        <div class="section-card">
            <div class="section-title">
                📊 Distribusi Siswa per Kelas
            </div>
            <div class="filter-buttons">
                <a href="{{ route('teacher.dashboard') }}" class="btn-filter {{ !$filterClass ? 'active' : '' }}">
                    Semua Kelas ({{ $totalStudents }})
                </a>
                @for ($i = 1; $i <= 6; $i++)
                    <a href="{{ route('teacher.dashboard', ['class' => $i]) }}"
                        class="btn-filter {{ $filterClass == $i ? 'active' : '' }}">
                        Kelas {{ $i }} ({{ $classStats[$i] ?? 0 }})
                    </a>
                @endfor
            </div>
        </div>

        <!-- Top Performers -->
        @if(count($topPerformers) > 0)
            <div class="section-card">
                <div class="section-title">
                    🏆 5 Siswa Terbaik
                </div>
                @foreach($topPerformers as $index => $performer)
                    <div class="top-performer-item">
                        <div class="performer-rank">#{{ $index + 1 }}</div>
                        <div class="performer-info">
                            <div class="performer-name">{{ $performer['student']->nama_anak }}</div>
                            <div class="performer-stats">
                                Kelas {{ $performer['student']->kelas }} •
                                {{ $performer['games_played'] }} games played
                            </div>
                        </div>
                        <div class="performer-score">{{ $performer['avg_score'] }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Students List -->
        <div class="section-card">
            <div class="section-title">
                📋 Daftar Siswa {{ $filterClass ? '(Kelas ' . $filterClass . ')' : '' }}
            </div>

            @if($students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-students">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Game Dimainkan</th>
                                <th>Rata-rata Skor</th>
                                <th>Accuracy</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                                @php
                                    $studentSessions = \App\Models\GameSession::where('student_id', $student->id)
                                        ->whereNotNull('completed_at')
                                        ->get();
                                    $gamesPlayed = $studentSessions->count();
                                    $avgScore = $gamesPlayed > 0 ? round($studentSessions->avg('total_score'), 2) : 0;
                                    $totalQ = $studentSessions->sum('total_questions');
                                    $totalC = $studentSessions->sum('correct_answers');
                                    $accuracy = $totalQ > 0 ? round(($totalC / $totalQ) * 100, 2) : 0;
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $student->nama_anak }}</strong></td>
                                    <td><span class="badge-class">Kelas {{ $student->kelas }}</span></td>
                                    <td>{{ $gamesPlayed }} games</td>
                                    <td><strong>{{ $avgScore }}</strong></td>
                                    <td>
                                        {{ $accuracy }}%
                                        <div class="progress-bar-custom">
                                            <div class="progress-fill" style="width: {{ $accuracy }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">📚</div>
                    <p>Tidak ada siswa {{ $filterClass ? 'di kelas ' . $filterClass : '' }}</p>
                </div>
            @endif
        </div>

        <!-- Recent Activities -->
        @if($recentSessions->count() > 0)
            <div class="section-card">
                <div class="section-title">
                    🕐 Recent Activities
                </div>
                <div class="table-responsive">
                    <table class="table table-activities">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Game</th>
                                <th>Skor</th>
                                <th>Accuracy</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentSessions as $session)
                                <tr>
                                    <td><strong>{{ $session->student->nama_anak }}</strong></td>
                                    <td>{{ $session->game->title }}</td>
                                    <td><strong>{{ $session->total_score }}</strong></td>
                                    <td>
                                        {{ $session->total_questions > 0 ? round(($session->correct_answers / $session->total_questions) * 100, 2) : 0 }}%
                                    </td>
                                    <td>{{ $session->completed_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const themeToggle = document.querySelector('.theme-toggle');
        const body = document.body;
        const storageKey = 'app-theme';

        const setTheme = (mode) => {
            body.classList.toggle('dark-mode', mode === 'dark');
            themeToggle.textContent = mode === 'dark' ? '☀️' : '🌙';
            themeToggle.setAttribute('aria-label', mode === 'dark' ? 'Tema terang' : 'Tema gelap');
        };

        const savedTheme = localStorage.getItem(storageKey);
        if (savedTheme === 'dark' || savedTheme === 'light') {
            setTheme(savedTheme);
        }

        themeToggle.addEventListener('click', () => {
            const nextTheme = body.classList.contains('dark-mode') ? 'light' : 'dark';
            localStorage.setItem(storageKey, nextTheme);
            setTheme(nextTheme);
        });

        // Search input behavior for dashboard filtering
        const searchBox = document.querySelector('.dashboard-search-box') || document.querySelector('.search-box');
        const searchIcon = document.querySelector('.dashboard-search-box .search-icon') || document.querySelector('.search-icon');
        const searchInput = document.getElementById('searchInput') || document.querySelector('.dashboard-search-box input') || document.querySelector('.search-box input');

        const showSearchInput = () => {
            if (searchBox) {
                searchBox.classList.remove('collapsed');
            }
        };

        showSearchInput();

        if (searchIcon && searchInput) {
            searchIcon.addEventListener('click', (e) => {
                e.preventDefault();
                searchInput.focus();
            });
        }

        if (searchBox && searchInput) {
            searchBox.addEventListener('click', () => {
                searchInput.focus();
            });
        }

        window.addEventListener('resize', showSearchInput);

        // Search functionality for dashboard sections.
        const topPerformerItems = document.querySelectorAll('.top-performer-item');
        const studentRows = document.querySelectorAll('.table-students tbody tr');
        const activityRows = document.querySelectorAll('.table-activities tbody tr');

        const matchesQuery = (text, query) => text.toLowerCase().includes(query);

        const filterElements = (elements, query) => {
            let visibleCount = 0;
            elements.forEach((el) => {
                const content = (el.textContent || '').replace(/\s+/g, ' ').trim();
                const isVisible = query === '' || matchesQuery(content, query);
                el.style.display = isVisible ? '' : 'none';
                if (isVisible) visibleCount++;
            });
            return visibleCount;
        };

        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const query = (e.target.value || '').trim().toLowerCase();
                filterElements(topPerformerItems, query);
                filterElements(studentRows, query);
                filterElements(activityRows, query);
            });
        }

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
