@extends('layouts.app')

@section('title', 'Dashboard — LibraryHub')

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

<!-- Sidebar Panel (Hidden by default, toggleable) -->
<aside class="sidebar-panel" id="sidebar">
    <button class="block absolute top-4 right-4 text-[#9aa6c7] hover:text-[#e6eef8] text-2xl" onclick="closeSidebar()">×</button>
    
    <div class="flex items-center gap-3 mb-4">
        <div class="w-12 h-12 bg-gradient-to-br from-[#00d4ff] to-[#a855f7] rounded-lg flex items-center justify-center font-bold text-[#050714]">LH</div>
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
            <button type="submit" class="w-full text-left text-red-400 hover:text-red-300 font-semibold text-sm transition py-2">Sign Out</button>
        </form>
    </div>
</aside>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <main class="space-y-6">
        @auth
            @if(auth()->user()->membership)
                <div class="glass-panel p-6 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-[#e6eef8] mb-2">Premium Member ✨</h3>
                            <p class="text-sm text-[#9aa6c7]">You have unlimited book borrowing privileges</p>
                        </div>
                        <div class="text-4xl">👑</div>
                    </div>
                </div>
            @else
                <div class="glass-panel p-6 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-[#e6eef8] mb-2">Upgrade to Membership</h3>
                            <p class="text-sm text-[#9aa6c7]">Get unlimited free book borrowing and exclusive benefits</p>
                        </div>
                        <button onclick="openUpgradeModal()" class="px-4 py-2 bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] rounded-md font-bold text-sm hover:opacity-90 transition">
                            Upgrade Now
                        </button>
                    </div>
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
        @endauth
    </main>
</div>

<!-- Membership Upgrade Modal -->
<div id="upgradeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-[#050714] border border-white/10 rounded-lg shadow-2xl p-8 max-w-md w-full mx-4">
        <h2 class="text-2xl font-bold text-[#e6eef8] mb-6">Upgrade to Membership</h2>
        
        <form action="{{ route('membership.upgrade') }}" method="POST">
            @csrf
            
            <label class="block text-sm font-semibold text-[#e6eef8] mb-4">Select Duration</label>
            <div class="space-y-3 mb-6">
                <div class="flex items-center">
                    <input type="radio" id="up_duration_1" name="duration" value="1" required class="mr-3">
                    <label for="up_duration_1" class="text-[#9aa6c7] flex-1">1 Month — RM 15.00</label>
                </div>
                <div class="flex items-center">
                    <input type="radio" id="up_duration_2" name="duration" value="2" required class="mr-3">
                    <label for="up_duration_2" class="text-[#9aa6c7] flex-1">2 Months — RM 27.50</label>
                </div>
                <div class="flex items-center">
                    <input type="radio" id="up_duration_3" name="duration" value="3" required class="mr-3">
                    <label for="up_duration_3" class="text-[#9aa6c7] flex-1">3 Months — RM 40.00</label>
                </div>
                <div class="flex items-center">
                    <input type="radio" id="up_duration_6" name="duration" value="6" required class="mr-3">
                    <label for="up_duration_6" class="text-[#9aa6c7] flex-1">6 Months — RM 60.00</label>
                </div>
            </div>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeUpgradeModal()" 
                        class="flex-1 px-4 py-2 border border-[#9aa6c7] text-[#9aa6c7] rounded-lg font-semibold hover:bg-white/5 transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] rounded-lg font-semibold hover:opacity-90 transition">
                    Confirm
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openUpgradeModal() {
    document.getElementById('upgradeModal').classList.remove('hidden');
}

function closeUpgradeModal() {
    document.getElementById('upgradeModal').classList.add('hidden');
}

// Close modal when pressing Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeUpgradeModal();
    }
});

// Close modal when clicking outside
document.getElementById('upgradeModal')?.addEventListener('click', (e) => {
    if (e.target.id === 'upgradeModal') {
        closeUpgradeModal();
    }
});
</script>

@endsection
