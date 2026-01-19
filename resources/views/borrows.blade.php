@extends('layouts.app')

@section('title', 'My Borrows — LibraryHub')

@section('content')
<style>
    .glass-panel { background: rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.05); backdrop-filter: blur(10px); }
    .nav-link { color:#9aa6c7; text-decoration:none; padding:10px 12px; border-radius:8px; font-weight:700; display:block }
    .nav-link:hover { color:#e6eef8; background:linear-gradient(90deg, rgba(0,212,255,0.04), rgba(168,85,247,0.03)); }
</style>

<!-- Sidebar Panel (toggleable) -->
<aside class="sidebar-panel">
    <div class="flex items-center gap-3 mb-4">
        <img src="/images/libraryHub-icon.svg" alt="LibraryHub" class="w-12 h-12">
        <div>
            <div class="font-semibold">LibraryHub</div>
            <div class="text-xs text-[#9aa6c7]">Menu</div>
        </div>
    </div>

    <nav class="flex flex-col gap-2 mb-4">
        <a href="{{ route('dashboard') }}" class="nav-link">Overview</a>
        <a href="{{ route('profile') }}" class="nav-link">My Profile</a>
        <a href="{{ route('books.catalogue') }}" class="nav-link">Book Catalogue</a>
        <a href="{{ route('borrows') }}" class="inline-block text-sm text-[#e6eef8] bg-gradient-to-r from-[#002b33]/10 to-[#3a003f]/6 px-3 py-2 rounded-md font-semibold">My Borrows</a>
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
            <button type="submit" class="w-full text-left text-red-400 hover:text-red-300 font-semibold text-sm transition py-2">Sign Out</button>
        </form>
    </div>
</aside>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <main class="space-y-6">
        <!-- Header -->
        <div class="glass-panel p-6 rounded-lg">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">My Borrows 📚</h1>
                    <p class="text-sm text-[#9aa6c7]">Track your borrowed books and borrowing history.</p>
                </div>
                <div class="text-5xl">📖</div>
            </div>
        </div>

        <!-- Statistics Cards -->
        @php
            $activeBorrows = auth()->user()->borrows()->whereNull('returned_at')->count();
            $returnedBorrows = auth()->user()->borrows()->whereNotNull('returned_at')->count();
            $totalBorrows = auth()->user()->borrows()->count();
            $avgBorrowDays = auth()->user()->borrows()
                ->whereNotNull('returned_at')
                ->selectRaw('AVG(DATEDIFF(returned_at, created_at)) as avg_days')
                ->first()
                ->avg_days ?? 0;
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="glass-panel p-6 rounded-lg border-l-4 border-[#00d4ff]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#9aa6c7] text-sm">Total Borrowed</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $totalBorrows }}</p>
                        <p class="text-xs text-[#9aa6c7] mt-1">All time</p>
                    </div>
                    <div class="text-4xl">📚</div>
                </div>
            </div>

            <div class="glass-panel p-6 rounded-lg border-l-4 border-[#a855f7]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#9aa6c7] text-sm">Active Borrows</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $activeBorrows }}</p>
                        <p class="text-xs text-[#9aa6c7] mt-1">Currently reading</p>
                    </div>
                    <div class="text-4xl">🎯</div>
                </div>
            </div>

            <div class="glass-panel p-6 rounded-lg border-l-4 border-[#00d4ff]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#9aa6c7] text-sm">Returned Books</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $returnedBorrows }}</p>
                        <p class="text-xs text-[#9aa6c7] mt-1">Completed</p>
                    </div>
                    <div class="text-4xl">✅</div>
                </div>
            </div>

            <div class="glass-panel p-6 rounded-lg border-l-4 border-[#a855f7]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#9aa6c7] text-sm">Avg Read Time</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ round($avgBorrowDays) }}</p>
                        <p class="text-xs text-[#9aa6c7] mt-1">days</p>
                    </div>
                    <div class="text-4xl">⏱️</div>
                </div>
            </div>
        </div>

        <!-- Active Borrows Section -->
        @if($activeBorrows > 0)
        <div class="glass-panel p-6 rounded-lg">
            <h2 class="text-xl font-bold text-white mb-4">Currently Reading 🎯</h2>
            @php $activeBorrowsList = auth()->user()->borrows()->whereNull('returned_at')->with('book')->latest()->get(); @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($activeBorrowsList as $borrow)
                <div class="bg-white/5 p-4 rounded-lg border-l-4 border-[#00d4ff]">
                    <p class="text-white font-semibold">{{ $borrow->book->title }}</p>
                    <p class="text-[#9aa6c7] text-sm mt-1">by {{ $borrow->book->author }}</p>
                    <p class="text-xs text-[#9aa6c7] mt-2">Borrowed {{ $borrow->created_at->diffForHumans() }}</p>
                    <a href="{{ route('books.show', $borrow->book) }}" class="mt-3 inline-block text-[#00d4ff] hover:text-[#a855f7] text-sm font-semibold">View Book →</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Borrow History Table -->
        <div class="glass-panel rounded-lg p-6">
            <h2 class="text-xl font-bold text-white mb-4">📖 Complete Borrow History</h2>

            @if($borrowHistory->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-[#9aa6c7]/10">
                                <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Book Title</th>
                                <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Author</th>
                                <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Borrowed</th>
                                <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Returned</th>
                                <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Status</th>
                                <th class="px-4 py-3 font-semibold text-[#9aa6c7]">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($borrowHistory as $borrow)
                                <tr class="border-b border-[#9aa6c7]/5 hover:bg-white/5 transition">
                                    <td class="px-4 py-3">
                                        <a href="{{ route('books.show', $borrow->book) }}" class="text-[#00d4ff] hover:text-[#a855f7] font-medium">{{ $borrow->book->title }}</a>
                                    </td>
                                    <td class="px-4 py-3 text-[#9aa6c7]">{{ $borrow->book->author }}</td>
                                    <td class="px-4 py-3 text-[#9aa6c7]">{{ $borrow->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-[#9aa6c7]">
                                        @if($borrow->returned_at)
                                            {{ $borrow->returned_at->format('M d, Y') }}
                                        @else
                                            <span class="text-[#a855f7] font-semibold">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($borrow->returned_at)
                                            <span class="inline-block px-3 py-1 bg-green-900/30 text-green-400 rounded-full text-xs font-semibold">Returned</span>
                                        @else
                                            <span class="inline-block px-3 py-1 bg-blue-900/30 text-blue-400 rounded-full text-xs font-semibold">Active</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('books.show', $borrow->book) }}" class="text-[#00d4ff] hover:text-[#a855f7] text-xs font-semibold">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $borrowHistory->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-5xl mb-4">📚</div>
                    <p class="text-lg text-[#9aa6c7] mb-2">No borrow history yet</p>
                    <p class="text-sm text-[#9aa6c7] mb-4">Start exploring our collection to begin borrowing books.</p>
                    <a href="{{ route('books.catalogue') }}" class="inline-block px-6 py-2 bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] rounded-md font-bold hover:opacity-90 transition">Browse Books Now</a>
                </div>
            @endif
        </div>
    </main>
</div>

@endsection
