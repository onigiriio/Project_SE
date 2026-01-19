@extends('layouts.app')

@section('title', 'Librarian Dashboard — LibraryHub')

@section('content')
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
        display: block;
    }

    .nav-link:hover {
        background: linear-gradient(90deg, rgba(0, 212, 255, 0.04), rgba(168, 85, 247, 0.03));
        color: #e6eef8;
        border: 1px solid rgba(0, 212, 255, 0.06);
    }
</style>

<!-- Sidebar Panel -->
<aside class="sidebar-panel" id="sidebar">
    <button class="block absolute top-4 right-4 text-[#9aa6c7] hover:text-[#e6eef8] text-2xl" onclick="closeSidebar()">×</button>
    
    <div class="flex items-center gap-3 mb-4">
        <img src="/images/libraryHub-icon.svg" alt="LibraryHub" class="w-12 h-12">
        <div>
            <div class="font-semibold">LibraryHub</div>
            <div class="text-xs text-[#9aa6c7]">Librarian</div>
        </div>
    </div>

    <nav class="flex flex-col gap-2 mb-4">
        <a href="{{ route('librarian.dashboard') }}" class="inline-block text-sm text-[#e6eef8] bg-gradient-to-r from-[#002b33]/10 to-[#3a003f]/6 px-3 py-2 rounded-md font-semibold">Overview</a>
        <a href="{{ route('books.catalogue') }}" class="nav-link">Manage Books</a>
        <a href="{{ route('librarian.users') }}" class="nav-link">Manage Users</a>
    </nav>

    <div class="mt-4 border-t border-[#9aa6c7]/10 pt-4">
        <div class="text-xs text-[#9aa6c7]">Logged in as</div>
        <div class="mt-3 flex items-center gap-3 bg-gradient-to-b from-white/2 to-transparent border border-white/5 p-3 rounded-md">
            <div class="w-10 h-10 rounded-md bg-gradient-to-br from-[#00d4ff] to-[#a855f7] flex items-center justify-center font-bold text-[#041029]">
                {{ strtoupper(substr(optional(auth()->user())->username ?? 'U',0,1)) }}
            </div>
            <div>
                <div class="font-semibold">{{ optional(auth()->user())->username ?? optional(auth()->user())->email }}</div>
                <div class="text-xs text-[#9aa6c7]">{{ ucfirst(optional(auth()->user())->user_type ?? 'user') }}</div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="w-full text-left text-red-400 hover:text-red-300 font-semibold text-sm transition py-2">Sign Out</button>
        </form>
    </div>
</aside>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <main class="space-y-6">
        <!-- Welcome Header -->
        <div class="glass-panel p-8 rounded-lg">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Welcome, {{ auth()->user()->username }}! 📚</h1>
                    <p class="text-[#9aa6c7]">Librarian Dashboard - Manage books and users</p>
                </div>
                <div class="text-5xl">📖</div>
            </div>
        </div>

        <!-- Library Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="glass-panel p-6 rounded-lg border-l-4 border-[#00d4ff]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#9aa6c7] text-sm">Total Books</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $totalBooks }}</p>
                        <p class="text-xs text-[#9aa6c7] mt-1">in catalogue</p>
                    </div>
                    <div class="text-4xl">📚</div>
                </div>
            </div>

            <div class="glass-panel p-6 rounded-lg border-l-4 border-[#a855f7]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#9aa6c7] text-sm">Total Users</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $totalUsers }}</p>
                        <p class="text-xs text-[#9aa6c7] mt-1">registered</p>
                    </div>
                    <div class="text-4xl">👥</div>
                </div>
            </div>

            <div class="glass-panel p-6 rounded-lg border-l-4 border-[#00d4ff]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#9aa6c7] text-sm">Active Borrows</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $activeBorrows }}</p>
                        <p class="text-xs text-[#9aa6c7] mt-1">in progress</p>
                    </div>
                    <div class="text-4xl">🎯</div>
                </div>
            </div>

            <div class="glass-panel p-6 rounded-lg border-l-4 border-[#a855f7]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#9aa6c7] text-sm">Premium Members</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $premiumMembers }}</p>
                        <p class="text-xs text-[#9aa6c7] mt-1">active</p>
                    </div>
                    <div class="text-4xl">✨</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="glass-panel p-6 rounded-lg">
            <h2 class="text-xl font-bold text-white mb-4">Quick Actions ⚡</h2>
            <div class="flex flex-col md:flex-row gap-3">
                <a href="{{ route('books.create') }}" class="px-6 py-3 bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] rounded-lg font-bold hover:opacity-90 transition text-center">
                    Add New Book
                </a>
                <a href="{{ route('books.catalogue') }}" class="px-6 py-3 bg-white/5 text-[#9aa6c7] rounded-lg font-bold hover:bg-white/10 transition text-center border border-[#9aa6c7]/30">
                    Manage Books
                </a>
                <a href="{{ route('librarian.users') }}" class="px-6 py-3 bg-white/5 text-[#9aa6c7] rounded-lg font-bold hover:bg-white/10 transition text-center border border-[#9aa6c7]/30">
                    Manage Users
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="glass-panel p-6 rounded-lg">
            <h2 class="text-xl font-bold text-white mb-4">Recent Activity 📊</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center bg-white/5 p-3 rounded-lg">
                    <span class="text-[#9aa6c7]">📖 Total Books Added</span>
                    <span class="text-white font-semibold">{{ $totalBooks }}</span>
                </div>
                <div class="flex justify-between items-center bg-white/5 p-3 rounded-lg">
                    <span class="text-[#9aa6c7]">👥 Total Registered Users</span>
                    <span class="text-white font-semibold">{{ $totalUsers }}</span>
                </div>
                <div class="flex justify-between items-center bg-white/5 p-3 rounded-lg">
                    <span class="text-[#9aa6c7]">🎯 Active Borrowings</span>
                    <span class="text-white font-semibold">{{ $activeBorrows }}</span>
                </div>
                <div class="flex justify-between items-center bg-white/5 p-3 rounded-lg">
                    <span class="text-[#9aa6c7]">✨ Premium Members</span>
                    <span class="text-white font-semibold">{{ $premiumMembers }}</span>
                </div>
            </div>
        </div>
    </main>
</div>

@endsection
