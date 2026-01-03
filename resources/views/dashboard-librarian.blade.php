@extends('layouts.app')

@section('title', 'Librarian Dashboard — LibraryHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-6">
        <!-- Sidebar -->
        <aside class="glass-panel p-5 rounded-lg h-full">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-[#00d4ff] to-[#a855f7] rounded-lg flex items-center justify-center font-bold text-[#050714]">LH</div>
                <div>
                    <div class="font-semibold">LibraryHub</div>
                    <div class="text-xs text-[#9aa6c7]">Librarian Panel</div>
                </div>
            </div>

            <nav class="flex flex-col gap-2 mb-4">
                <a href="{{ route('dashboard') }}" class="inline-block text-sm text-[#e6eef8] bg-gradient-to-r from-[#002b33]/10 to-[#3a003f]/6 px-3 py-2 rounded-md font-semibold">Dashboard</a>
                <a href="{{ route('books.index') }}" class="nav-link">Manage Books</a>
                <a href="#" class="nav-link">Manage Users</a>
                <a href="#" class="nav-link">Borrow Records</a>
                <a href="#" class="nav-link">Reports</a>
                <a href="#" class="nav-link">Settings</a>
            </nav>

            <div class="mt-4 border-t border-[#9aa6c7]/10 pt-4">
                <div class="text-xs text-[#9aa6c7]">Logged in as</div>
                <div class="mt-3 flex items-center gap-3 bg-gradient-to-b from-white/2 to-transparent border border-white/5 p-3 rounded-md">
                    <div class="w-10 h-10 rounded-md bg-gradient-to-br from-[#00d4ff] to-[#a855f7] flex items-center justify-center font-bold text-[#041029]">
                        {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-semibold">{{ auth()->user()->username }}</div>
                        <div class="text-xs text-[#9aa6c7]">{{ ucfirst(auth()->user()->user_type) }}</div>
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
                    <h1 class="text-2xl font-bold">Welcome back, {{ auth()->user()->username }}</h1>
                    <p class="text-sm text-[#9aa6c7]">Manage your library system efficiently.</p>
                </div>
                <div class="text-right">
                    <div class="text-xs text-[#9aa6c7]">Role</div>
                    <div class="font-extrabold">{{ ucfirst(auth()->user()->user_type) }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
                <div class="glass-panel p-4 rounded-lg">
                    <div class="text-2xl font-extrabold gradient-text">{{ \App\Models\Book::count() }}</div>
                    <div class="text-xs text-[#9aa6c7] mt-1">Total Books</div>
                </div>
                <div class="glass-panel p-4 rounded-lg">
                    <div class="text-2xl font-extrabold gradient-text">{{ \App\Models\User::where('user_type', 'user')->count() }}</div>
                    <div class="text-xs text-[#9aa6c7] mt-1">Registered Users</div>
                </div>
                <div class="glass-panel p-4 rounded-lg">
                    <div class="text-2xl font-extrabold gradient-text">{{ \App\Models\Borrow::whereNull('returned_at')->count() }}</div>
                    <div class="text-xs text-[#9aa6c7] mt-1">Active Borrows</div>
                </div>
                <div class="glass-panel p-4 rounded-lg">
                    <div class="text-2xl font-extrabold gradient-text">{{ \App\Models\Review::count() }}</div>
                    <div class="text-xs text-[#9aa6c7] mt-1">Total Reviews</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="glass-panel p-4 rounded-lg">
                    <h3 class="font-semibold mb-3">Recent Activity</h3>
                    <div class="space-y-3">
                        @php
                            $recentBorrows = \App\Models\Borrow::with('user', 'book')->latest()->take(3)->get();
                        @endphp
                        @forelse($recentBorrows as $borrow)
                        <div class="flex justify-between items-center bg-white/2 p-3 rounded-md">
                            <div>📚 {{ $borrow->user->username }} borrowed "{{ $borrow->book->title }}"</div>
                            <div class="text-xs text-[#9aa6c7]">{{ $borrow->borrowed_at->diffForHumans() }}</div>
                        </div>
                        @empty
                        <div class="text-sm text-[#9aa6c7]">No recent borrows</div>
                        @endforelse
                    </div>
                </div>

                <div class="glass-panel p-4 rounded-lg">
                    <h3 class="font-semibold mb-3">Quick Actions</h3>
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('books.create') }}" class="py-2 rounded-md bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#041029] font-bold text-center">Add New Book</a>
                        <a href="{{ route('books.index') }}" class="py-2 rounded-md bg-white/3 text-[#9aa6c7] text-center">Manage Books</a>
                        <a href="#" class="py-2 rounded-md bg-white/3 text-[#9aa6c7] text-center">View Reports</a>
                    </div>
                </div>
            </div>

            <div class="mt-6 glass-panel p-4 rounded-lg">
                <h3 class="font-semibold mb-2">Library Management Notes</h3>
                <p class="text-sm text-[#9aa6c7]">Keep track of book inventory, manage user accounts, and monitor borrowing activities. Use the sidebar to access all management features.</p>
            </div>
        </main>
    </div>
</div>
@endsection