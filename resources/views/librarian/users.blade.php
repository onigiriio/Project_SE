@extends('layouts.app')

@section('title', 'Manage Users — IIUM Library Management System')

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
        <img src="/images/libraryHub-icon.svg" alt="IIUM Library Management System" class="w-12 h-12">
        <div>
            <div class="font-semibold">IIUM Library Management System</div>
            <div class="text-xs text-[#9aa6c7]">Librarian</div>
        </div>
    </div>

    <nav class="flex flex-col gap-2 mb-4">
        <a href="{{ route('librarian.dashboard') }}" class="nav-link">Overview</a>
        <a href="{{ route('books.catalogue') }}" class="nav-link">Manage Books</a>
        <a href="{{ route('librarian.users') }}" class="inline-block text-sm text-[#e6eef8] bg-gradient-to-r from-[#002b33]/10 to-[#3a003f]/6 px-3 py-2 rounded-md font-semibold">Manage Users</a>
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
        <!-- Header -->
        <div class="glass-panel p-6 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Manage Users 👥</h1>
                    <p class="text-[#9aa6c7]">View all users and their borrowing activity</p>
                </div>
                <div class="text-5xl">📋</div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="glass-panel rounded-lg p-6">
            <h2 class="text-xl font-bold text-white mb-4">All Users</h2>
            
            @if($users->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-[#9aa6c7]/10">
                                <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Username</th>
                                <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Email</th>
                                <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Status</th>
                                <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Books Borrowed</th>
                                <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Member Since</th>
                                <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr class="border-b border-[#9aa6c7]/5 hover:bg-white/5 transition">
                                    <td class="px-4 py-3 text-white font-semibold">{{ $user->username }}</td>
                                    <td class="px-4 py-3 text-[#9aa6c7]">{{ $user->email }}</td>
                                    <td class="px-4 py-3">
                                        @if($user->membership)
                                            <span class="inline-block px-3 py-1 bg-green-900/30 text-green-400 rounded-full text-xs font-semibold">Premium</span>
                                        @else
                                            <span class="inline-block px-3 py-1 bg-blue-900/30 text-blue-400 rounded-full text-xs font-semibold">Standard</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-white">{{ $user->borrows()->count() }}</td>
                                    <td class="px-4 py-3 text-[#9aa6c7]">{{ $user->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-3">
                                        <button onclick="showUserDetails({{ $user->id }})" class="text-[#00d4ff] hover:text-[#a855f7] text-xs font-semibold transition">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-[#9aa6c7]">No users found</p>
                </div>
            @endif
        </div>

        <!-- Active Borrowings Summary -->
        <div class="glass-panel rounded-lg p-6">
            <h2 class="text-xl font-bold text-white mb-4">Current Active Borrowings 📖</h2>
            
            @if($activeBorrows->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-[#9aa6c7]/10">
                                <th class="px-4 py-3 font-semibold text-[#9aa6c7]">User</th>
                                <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Book Title</th>
                                <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Author</th>
                                <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Borrowed Date</th>
                                <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeBorrows as $borrow)
                                <tr class="border-b border-[#9aa6c7]/5 hover:bg-white/5 transition">
                                    <td class="px-4 py-3 text-white font-semibold">{{ $borrow->user->username }}</td>
                                    <td class="px-4 py-3 text-[#00d4ff]">{{ $borrow->book->title }}</td>
                                    <td class="px-4 py-3 text-[#9aa6c7]">{{ $borrow->book->author }}</td>
                                    <td class="px-4 py-3 text-[#9aa6c7]">{{ $borrow->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-3">
                                        <button onclick="showUserDetails({{ $borrow->user->id }})" class="text-[#00d4ff] hover:text-[#a855f7] text-xs font-semibold transition">
                                            View User
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-[#9aa6c7]">No active borrowings currently</p>
                </div>
            @endif
        </div>
    </main>
</div>

<!-- User Details Modal -->
<div id="userDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-[#050714] border border-white/10 rounded-lg shadow-2xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white">User Details</h2>
            <button onclick="closeUserDetails()" class="text-[#9aa6c7] hover:text-white text-2xl">×</button>
        </div>

        <div id="userDetailsContent">
            <!-- Loading -->
            <p class="text-[#9aa6c7]">Loading...</p>
        </div>
    </div>
</div>

<script>
function showUserDetails(userId) {
    fetch(`/librarian/users/${userId}/details`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('userDetailsContent').innerHTML = html;
            document.getElementById('userDetailsModal').classList.remove('hidden');
        })
        .catch(error => console.error('Error:', error));
}

function closeUserDetails() {
    document.getElementById('userDetailsModal').classList.add('hidden');
}

document.getElementById('userDetailsModal')?.addEventListener('click', (e) => {
    if (e.target.id === 'userDetailsModal') {
        closeUserDetails();
    }
});
</script>

@endsection
