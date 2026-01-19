@extends('layouts.app')

@section('title', 'Dashboard — LibraryHub')

@section('content')
<style>
    .menu-icon {
        position: fixed;
        top: 80px;
        left: 16px;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #00d4ff, #a855f7);
        border: none;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #050714;
        font-weight: bold;
        z-index: 45;
        transition: all 0.3s ease;
    }

    .menu-icon:hover {
        transform: scale(1.05);
    }

    .glass-panel {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
    }

    .nav-link {
        color: #9aa6c7;
        text-decoration: none;
        padding: 10px 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .nav-link:hover {
        background: linear-gradient(90deg, rgba(0, 212, 255, 0.04), rgba(168, 85, 247, 0.03));
        color: #e6eef8;
        border: 1px solid rgba(0, 212, 255, 0.06);
    }

    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 260px;
        height: 100vh;
        z-index: 40;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        margin-top: 0;
        padding-top: 80px;
    }

    .sidebar.open {
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

    .main-content {
        margin-left: 0;
    }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Menu Toggle Icon (Top Left) -->
    <button class="menu-icon" onclick="toggleSidebar()" title="Toggle Menu">☰</button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <div class="grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-6">
        <!-- Sidebar -->
        <aside class="sidebar glass-panel p-5 rounded-lg h-full" id="sidebar">
            <button class="absolute top-4 right-4 lg:hidden text-[#9aa6c7] hover:text-[#e6eef8] text-2xl" onclick="closeSidebar()">×</button>
            
            <div class="flex items-center gap-3 mb-4">
                <img src="/images/libraryHub-icon.svg" alt="LibraryHub" class="w-12 h-12">
                <div>
                    <div class="font-semibold">LibraryHub</div>
                    <div class="text-xs text-[#9aa6c7]">Menu</div>
                </div>
            </div>

            <nav class="flex flex-col gap-2 mb-4">
                        <a href="{{ route('dashboard') }}" class="inline-block text-sm text-[#e6eef8] bg-gradient-to-r from-[#002b33]/10 to-[#3a003f]/6 px-3 py-2 rounded-md font-semibold">Overview</a>
                        <a href="{{ route('profile') }}" class="nav-link">My Profile</a>
                        <a href="{{ route('books.catalogue') }}" class="nav-link">Book Catalogue</a>
                        <a href="{{ route('borrows') }}" class="nav-link">My Borrows</a>
                    </nav>

            <div class="mt-4 border-t border-[#9aa6c7]/10 pt-4">
                <div class="text-xs text-[#9aa6c7]">Logged in as</div>
                <div class="mt-3 flex items-center gap-3 bg-gradient-to-b from-white/2 to-transparent border border-white/5 p-3 rounded-md">
                    <div class="w-10 h-10 rounded-md bg-gradient-to-br from-[#00d4ff] to-[#a855f7] flex items-center justify-center font-bold text-[#041029]">
                        {{ strtoupper(substr(optional(auth()->user())->name ?? 'U',0,1)) }}
                    </div>
                    <div>
                        <div class="font-semibold">{{ optional(auth()->user())->username ?? optional(auth()->user())->email }}</div>
                        <div class="text-xs text-[#9aa6c7]">{{ ucfirst(optional(auth()->user())->user_type ?? 'user') }}</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full py-2 rounded-md bg-gradient-to-r from-[#ff6b6b] to-[#ff3b3b] text-[#041029] font-bold">Sign Out</button>
                </form>
            </div>
        </aside>

        <!-- Main -->
        <main>
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold">Welcome back, {{ optional(auth()->user())->username ?? optional(auth()->user())->name ?? 'User' }}</h1>
                    <p class="text-sm text-[#9aa6c7]">Here's a quick overview of system health and activity.</p>
                </div>
                <div class="text-right">
                    <div class="text-xs text-[#9aa6c7]">Membership</div>
                    <div class="font-extrabold">{{ auth()->user()->membership ? 'Active' : 'None' }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="glass-panel p-4 rounded-lg">
                    <div class="text-2xl font-extrabold gradient-text">10,482</div>
                    <div class="text-xs text-[#9aa6c7] mt-1">Total Resources</div>
                </div>
                <div class="glass-panel p-4 rounded-lg">
                    <div class="text-2xl font-extrabold gradient-text">5,210</div>
                    <div class="text-xs text-[#9aa6c7] mt-1">Registered Users</div>
                </div>
                <div class="glass-panel p-4 rounded-lg">
                    <div class="text-2xl font-extrabold gradient-text">99.97%</div>
                    <div class="text-xs text-[#9aa6c7] mt-1">Uptime</div>
                </div>
            </div>

            @if(auth()->user()->user_type !== 'librarian')
                <!-- Membership Details Card (For Members Only) -->
                <div class="mb-6">
                    @if(auth()->user()->membership)
                        <div class="glass-panel rounded-lg p-6 bg-gradient-to-br from-[#00d4ff]/5 to-[#a855f7]/5 border border-[#00d4ff]/20">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="text-3xl">✓</div>
                                        <div>
                                            <h3 class="text-xl font-bold text-[#00d4ff]">ACTIVE MEMBERSHIP</h3>
                                            <p class="text-sm text-[#9aa6c7] mt-1">Enjoy exclusive benefits and free book borrowing</p>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('profile') }}" class="px-4 py-2 bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] rounded-md font-bold text-sm hover:opacity-90 transition">
                                    View Details
                                </a>
                            </div>
                            
                            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="bg-white/3 p-3 rounded-md text-center">
                                    <div class="text-2xl font-bold text-[#00d4ff]">∞</div>
                                    <p class="text-xs text-[#9aa6c7] mt-1">Free Borrows</p>
                                </div>
                                <div class="bg-white/3 p-3 rounded-md text-center">
                                    <div class="text-2xl font-bold text-[#00d4ff]">∞</div>
                                    <p class="text-xs text-[#9aa6c7] mt-1">Borrowing Limit</p>
                                </div>
                                <div class="bg-white/3 p-3 rounded-md text-center">
                                    <div class="text-2xl font-bold text-[#00d4ff]">30</div>
                                    <p class="text-xs text-[#9aa6c7] mt-1">Days to Return</p>
                                </div>
                                <div class="bg-white/3 p-3 rounded-md text-center">
                                    <div class="text-2xl font-bold text-[#00d4ff]">24/7</div>
                                    <p class="text-xs text-[#9aa6c7] mt-1">Support Access</p>
                                </div>
                            </div>
                            
                            @if(auth()->user()->membership_date)
                                <p class="text-xs text-[#9aa6c7] mt-4">Member since: {{ auth()->user()->membership_date->format('M d, Y') }}</p>
                            @endif
                        </div>
                    @else
                        <div class="glass-panel rounded-lg p-6 bg-gradient-to-br from-[#9aa6c7]/5 to-[#9aa6c7]/2 border border-[#9aa6c7]/10">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-[#e6eef8] mb-2">Upgrade to Membership</h3>
                                    <p class="text-sm text-[#9aa6c7]">Get unlimited free book borrowing and exclusive benefits</p>
                                </div>
                                <a href="{{ route('profile') }}" class="px-4 py-2 bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] rounded-md font-bold text-sm hover:opacity-90 transition">
                                    Learn More
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="glass-panel p-4 rounded-lg">
                    <h3 class="font-semibold mb-3">Recent Activity</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center bg-white/2 p-3 rounded-md">
                            <div>📚 User John borrowed "Introduction to AI"</div>
                            <div class="text-xs text-[#9aa6c7]">2m ago</div>
                        </div>
                        <div class="flex justify-between items-center bg-white/2 p-3 rounded-md">
                            <div>🔁 Catalog sync completed</div>
                            <div class="text-xs text-[#9aa6c7]">10m ago</div>
                        </div>
                        <div class="flex justify-between items-center bg-white/2 p-3 rounded-md">
                            <div>🛠️ Backup finished</div>
                            <div class="text-xs text-[#9aa6c7]">1h ago</div>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->user_type === 'librarian')
                <div class="glass-panel p-4 rounded-lg">
                    <h3 class="font-semibold mb-3">Quick Actions</h3>
                    <div class="flex flex-col gap-3">
                        <a href="#" class="py-2 rounded-md bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#041029] font-bold text-center">Add Resource</a>
                        <a href="#" class="py-2 rounded-md bg-white/3 text-[#9aa6c7] text-center">Manage Users</a>
                        <a href="#" class="py-2 rounded-md bg-white/3 text-[#9aa6c7] text-center">View Analytics</a>
                    </div>
                </div>
                @endif
            </div>

            <div class="mt-6 glass-panel p-4 rounded-lg">
                <h3 class="font-semibold mb-2">System Notes</h3>
                <p class="text-sm text-[#9aa6c7]">All systems nominal. If you notice any irregularities, go to Settings → Diagnostics.</p>
            </div>
        </main>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');
    }

    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
    }

    // Close sidebar when clicking on a link
    document.querySelectorAll('#sidebar a').forEach(link => {
        link.addEventListener('click', closeSidebar);
    });
</script>

@endsection
