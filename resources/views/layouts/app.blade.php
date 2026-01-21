<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IIUM Library Management System - Book Catalogue')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600|space-mono:400,700" rel="stylesheet" />
    <style>
        body {
            font-family: 'Instrument Sans', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
        }
        
        :root {
            --bg-1: #050714;
            --panel: rgba(255,255,255,0.04);
            --accent-a: #00d4ff;
            --accent-b: #a855f7;
            --muted: #9aa6c7;
            --glass-border: rgba(0,212,255,0.12);
        }

        .app-bg {
            background: radial-gradient(1200px 600px at 10% 10%, rgba(0,212,255,0.06), transparent),
                        radial-gradient(800px 400px at 90% 80%, rgba(168,85,247,0.05), transparent),
                        linear-gradient(180deg, var(--bg-1), #081026);
        }

        .glass-panel {
            background: var(--panel);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(8px) saturate(120%);
        }

        .navbar {
            background: linear-gradient(90deg, rgba(5,7,20,0.8), rgba(8,16,38,0.8));
            border-bottom: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
        }

        .nav-link {
            color: var(--muted);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .nav-link:hover {
            color: #e6eef8;
            background: linear-gradient(90deg, rgba(0,212,255,0.06), rgba(168,85,247,0.03));
            border: 1px solid rgba(0,212,255,0.08);
        }

        .gradient-text {
            background: linear-gradient(90deg, #00d4ff, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hamburger {
            width: 32px;
            height: 32px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            background: linear-gradient(135deg, #00d4ff, #a855f7);
            border: none;
            border-radius: 6px;
            padding: 6px;
            transition: all 0.3s ease;
        }

        .hamburger:hover {
            transform: scale(1.05);
        }

        .hamburger span {
            width: 100%;
            height: 2px;
            background: #050714;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .sidebar-panel {
            position: fixed;
            left: 0;
            top: 60px;
            width: 260px;
            height: calc(100vh - 60px);
            z-index: 40;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-left: none;
            backdrop-filter: blur(10px);
            padding: 20px;
        }

        .sidebar-panel.open {
            transform: translateX(0);
        }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 30;
            display: none;
        }

        .sidebar-overlay.open {
            display: block;
        }
    </style>
</head>
<body class="app-bg text-white min-h-screen">
    <!-- Navigation Bar -->
    <nav class="navbar sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo & Menu Icon -->
                <div class="flex items-center gap-3">
                    <button class="hamburger" id="hamburger" onclick="toggleSidebar()" title="Toggle Menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <img src="/images/libraryHub-icon.svg" alt="IIUM Library Management System" class="w-10 h-10">
                    <div>
                        <div class="font-bold text-lg">IIUM Library Management System</div>
                        <div class="text-xs text-[#9aa6c7]">Book Catalogue</div>
                    </div>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="flex items-center gap-6">
                    @auth
                        <div class="pl-6 border-l border-[#9aa6c7]">
                            <span class="text-sm text-[#9aa6c7]">{{ auth()->user()->username }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline-block ml-3">
                                @csrf
                                <button type="submit" class="text-red-400 hover:text-red-300 font-semibold text-sm transition">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="border-t border-[#9aa6c7]/20 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-[#9aa6c7] text-sm">
                <p>&copy; 2026 IIUM Library Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleSidebar() {
            const overlay = document.getElementById('sidebarOverlay');
            const sidebars = document.querySelectorAll('.sidebar-panel');
            
            sidebars.forEach(sidebar => {
                sidebar.classList.toggle('open');
            });
            overlay.classList.toggle('open');
        }

        function closeSidebar() {
            const overlay = document.getElementById('sidebarOverlay');
            const sidebars = document.querySelectorAll('.sidebar-panel');
            
            sidebars.forEach(sidebar => {
                sidebar.classList.remove('open');
            });
            overlay.classList.remove('open');
        }

        // Close sidebar when clicking on links
        document.querySelectorAll('.sidebar-panel a').forEach(link => {
            link.addEventListener('click', closeSidebar);
        });
    </script>

    @yield('scripts')
</body>
</html>
