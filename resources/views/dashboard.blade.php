@extends('layouts.app')

@section('title', 'Dashboard — LibraryHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-6">
        <!-- Sidebar -->
        <aside class="glass-panel p-5 rounded-lg h-full">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-[#00d4ff] to-[#a855f7] rounded-lg flex items-center justify-center font-bold text-[#050714]">LH</div>
                <div>
                    <div class="font-semibold">LibraryHub</div>
                    <div class="text-xs text-[#9aa6c7]">System — Full View</div>
                </div>
            </div>

            <nav class="flex flex-col gap-2 mb-4">
                <a href="{{ route('dashboard') }}" class="inline-block text-sm text-[#e6eef8] bg-gradient-to-r from-[#002b33]/10 to-[#3a003f]/6 px-3 py-2 rounded-md font-semibold">Overview</a>
                <a href="{{ route('books.catalogue') }}" class="nav-link">Book Catalogue</a>
                <a href="#" class="nav-link">Users</a>
                <a href="#" class="nav-link">Analytics</a>
                <a href="#" class="nav-link">Settings</a>
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

                <div class="glass-panel p-4 rounded-lg">
                    <h3 class="font-semibold mb-3">Quick Actions</h3>
                    <div class="flex flex-col gap-3">
                        <a href="#" class="py-2 rounded-md bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#041029] font-bold text-center">Add Resource</a>
                        <a href="#" class="py-2 rounded-md bg-white/3 text-[#9aa6c7] text-center">Manage Users</a>
                        <a href="#" class="py-2 rounded-md bg-white/3 text-[#9aa6c7] text-center">View Analytics</a>
                    </div>
                </div>
            </div>

            <div class="mt-6 glass-panel p-4 rounded-lg">
                <h3 class="font-semibold mb-2">System Notes</h3>
                <p class="text-sm text-[#9aa6c7]">All systems nominal. If you notice any irregularities, go to Settings → Diagnostics.</p>
            </div>
        </main>
    </div>
</div>
@endsection

