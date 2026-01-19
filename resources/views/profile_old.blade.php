@extends('layouts.app')

@section('title', 'My Profile — LibraryHub')

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
                <div class="w-12 h-12 bg-gradient-to-br from-[#00d4ff] to-[#a855f7] rounded-lg flex items-center justify-center font-bold text-[#050714]">LH</div>
                <div>
                    <div class="font-semibold">LibraryHub</div>
                    <div class="text-xs text-[#9aa6c7]">Menu</div>
                </div>
            </div>

            <nav class="flex flex-col gap-2 mb-4">
                <a href="{{ route('dashboard') }}" class="nav-link">Overview</a>
                <a href="{{ route('profile') }}" class="inline-block text-sm text-[#e6eef8] bg-gradient-to-r from-[#002b33]/10 to-[#3a003f]/6 px-3 py-2 rounded-md font-semibold">My Profile</a>
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

        <!-- Main Content -->
        <main>
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-[#e6eef8]">My Profile</h1>
                <p class="text-sm text-[#9aa6c7] mt-2">View and manage your account details</p>
            </div>

            <!-- User Details Card -->
            <div class="glass-panel rounded-lg p-8 mb-8">
                <div class="flex items-start justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-[#e6eef8] mb-2">{{ $user->name ?? $user->username }}</h2>
                        <p class="text-[#9aa6c7]">{{ $user->email }}</p>
                    </div>
                    <div class="w-20 h-20 bg-gradient-to-br from-[#00d4ff] to-[#a855f7] rounded-full flex items-center justify-center font-bold text-[#050714] text-3xl">
                        {{ strtoupper(substr($user->name ?? $user->username ?? 'U', 0, 1)) }}
                    </div>
                </div>

                <!-- Account Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Personal Details -->
                    <div>
                        <h3 class="text-lg font-semibold text-[#e6eef8] mb-6">Personal Details</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-[#9aa6c7]">USERNAME</label>
                                <p class="text-[#e6eef8] mt-1 text-lg font-medium">{{ $user->username }}</p>
                            </div>
                            
                            <div>
                                <label class="text-xs font-semibold text-[#9aa6c7]">EMAIL ADDRESS</label>
                                <p class="text-[#e6eef8] mt-1 text-lg font-medium">{{ $user->email }}</p>
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-[#9aa6c7]">ACCOUNT TYPE</label>
                                <p class="text-[#e6eef8] mt-1 text-lg font-medium capitalize">{{ $user->user_type }}</p>
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-[#9aa6c7]">JOINED DATE</label>
                                <p class="text-[#e6eef8] mt-1 text-lg font-medium">{{ $user->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Membership Details -->
                    <div>
                        <h3 class="text-lg font-semibold text-[#e6eef8] mb-6">Membership Status</h3>
                        
                        @if($user->membership)
                            <div class="bg-gradient-to-br from-[#00d4ff]/10 to-[#a855f7]/10 border border-[#00d4ff]/30 rounded-lg p-6">
                                <div class="flex items-start gap-4">
                                    <div class="text-3xl">✓</div>
                                    <div>
                                        <h4 class="text-lg font-bold text-[#00d4ff] mb-2">ACTIVE MEMBER</h4>
                                        <ul class="space-y-2 text-sm text-[#9aa6c7]">
                                            <li class="flex items-center gap-2">
                                                <span class="text-[#00d4ff]">✓</span>
                                                Free borrowing on all books
                                            </li>
                                            <li class="flex items-center gap-2">
                                                <span class="text-[#00d4ff]">✓</span>
                                                Unlimited borrowing limit
                                            </li>
                                            <li class="flex items-center gap-2">
                                                <span class="text-[#00d4ff]">✓</span>
                                                Access to exclusive deals
                                            </li>
                                            <li class="flex items-center gap-2">
                                                <span class="text-[#00d4ff]">✓</span>
                                                Extended borrowing period
                                            </li>
                                        </ul>
                                        @if($user->membership_date)
                                            <p class="text-xs text-[#9aa6c7] mt-4">Member since: {{ $user->membership_date->format('M d, Y') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-gradient-to-br from-[#9aa6c7]/10 to-[#9aa6c7]/5 border border-[#9aa6c7]/20 rounded-lg p-6">
                                <h4 class="text-lg font-bold text-[#e6eef8] mb-3">NO ACTIVE MEMBERSHIP</h4>
                                <p class="text-sm text-[#9aa6c7] mb-4">Upgrade to membership to enjoy exclusive benefits and free book borrowing!</p>
                                <button class="px-4 py-2 bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] rounded-md font-bold hover:opacity-90 transition">
                                    Upgrade to Member
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Borrow History -->
            <div class="glass-panel rounded-lg p-8">
                <h2 class="text-2xl font-bold text-[#e6eef8] mb-6">Borrow History</h2>

                @if($borrowHistory->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-[#9aa6c7]/10">
                                    <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Book Title</th>
                                    <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Author</th>
                                    <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Borrowed Date</th>
                                    <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Return Date</th>
                                    <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($borrowHistory as $borrow)
                                    <tr class="border-b border-[#9aa6c7]/5 hover:bg-white/5 transition">
                                        <td class="px-4 py-3">
                                            <a href="{{ route('books.show', $borrow->book) }}" class="text-[#00d4ff] hover:text-[#a855f7] font-medium">
                                                {{ $borrow->book->title }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-[#9aa6c7]">{{ $borrow->book->author }}</td>
                                        <td class="px-4 py-3 text-[#9aa6c7]">{{ $borrow->created_at->format('M d, Y') }}</td>
                                        <td class="px-4 py-3 text-[#9aa6c7]">
                                            @if($borrow->returned_at)
                                                {{ $borrow->returned_at->format('M d, Y') }}
                                            @else
                                                <span class="text-[#a855f7]">Still borrowed</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($borrow->returned_at)
                                                <span class="inline-block px-3 py-1 bg-green-900/30 text-green-400 rounded-full text-xs font-semibold">Returned</span>
                                            @else
                                                <span class="inline-block px-3 py-1 bg-blue-900/30 text-blue-400 rounded-full text-xs font-semibold">Active</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $borrowHistory->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-4xl mb-4">📚</div>
                        <p class="text-[#9aa6c7]">You haven't borrowed any books yet.</p>
                        <a href="{{ route('books.catalogue') }}" class="mt-4 inline-block px-6 py-2 bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] rounded-md font-bold hover:opacity-90 transition">
                            Browse Books Now
                        </a>
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>

<style>
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
</style>

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

