@extends('layouts.app')

@section('title', 'Dashboard — IIUM Library Management System')

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
        <img src="/images/libraryHub-icon.svg" alt="IIUM Library Management System" class="w-12 h-12">
        <div>
            <div class="font-semibold">IIUM Library Management System</div>
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
        <!-- Welcome Header -->
        <div class="glass-panel p-8 rounded-lg">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Welcome, {{ auth()->user()->username }}!</h1>
                    <p class="text-[#9aa6c7]">Here's what's happening in your library</p>
                </div>
                <div class="text-5xl">📖</div>
            </div>
        </div>

        @auth
            <!-- Membership & Status Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @if(auth()->user()->membership)
                    <div class="glass-panel p-6 rounded-lg border-l-4 border-[#00d4ff]">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[#9aa6c7] text-sm">Membership Status</p>
                                <p class="text-2xl font-bold text-white mt-2">Premium ✨</p>
                                <p class="text-xs text-[#9aa6c7] mt-1">Until {{ auth()->user()->membership_expiry->format('M d, Y') }}</p>
                            </div>
                            <div class="text-4xl">👑</div>
                        </div>
                    </div>
                @else
                    <div class="glass-panel p-6 rounded-lg border-l-4 border-[#a855f7]">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[#9aa6c7] text-sm">Membership</p>
                                <p class="text-2xl font-bold text-white mt-2">Standard</p>
                                <button onclick="openUpgradeModal()" class="text-xs text-[#00d4ff] hover:text-[#a855f7] mt-1 font-semibold">Upgrade →</button>
                            </div>
                            <div class="text-4xl">🔓</div>
                        </div>
                    </div>
                @endif

                <div class="glass-panel p-6 rounded-lg border-l-4 border-[#a855f7]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[#9aa6c7] text-sm">Books Borrowed</p>
                            <p class="text-2xl font-bold text-white mt-2">{{ auth()->user()->borrows()->whereNull('returned_at')->count() }}</p>
                            <p class="text-xs text-[#9aa6c7] mt-1">Currently active</p>
                        </div>
                        <div class="text-4xl">📚</div>
                    </div>
                </div>

                <div class="glass-panel p-6 rounded-lg border-l-4 border-[#00d4ff]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[#9aa6c7] text-sm">Total Borrowed</p>
                            <p class="text-2xl font-bold text-white mt-2">{{ auth()->user()->borrows()->count() }}</p>
                            <p class="text-xs text-[#9aa6c7] mt-1">All time</p>
                        </div>
                        <div class="text-4xl">🎯</div>
                    </div>
                </div>

                <div class="glass-panel p-6 rounded-lg border-l-4 border-[#a855f7]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[#9aa6c7] text-sm">Member Since</p>
                            <p class="text-2xl font-bold text-white mt-2">{{ auth()->user()->created_at->format('M Y') }}</p>
                            <p class="text-xs text-[#9aa6c7] mt-1">{{ auth()->user()->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-4xl">⭐</div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Recent & Trending -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Current Borrows -->
                    <div class="glass-panel p-6 rounded-lg">
                        <h2 class="text-xl font-bold text-white mb-4">Your Active Borrows 📚</h2>
                        @php $activeBorrows = auth()->user()->borrows()->whereNull('returned_at')->with('book')->latest()->take(5)->get(); @endphp
                        @forelse($activeBorrows as $borrow)
                            <div class="flex items-center justify-between bg-white/5 p-4 rounded-lg mb-3 hover:bg-white/10 transition">
                                <div class="flex-1">
                                    <p class="text-white font-semibold">{{ $borrow->book->title }}</p>
                                    <p class="text-sm text-[#9aa6c7]">by {{ $borrow->book->author }}</p>
                                    <p class="text-xs text-[#9aa6c7] mt-1">Borrowed {{ $borrow->created_at->diffForHumans() }}</p>
                                </div>
                                <a href="{{ route('books.show', $borrow->book) }}" class="text-[#00d4ff] hover:text-[#a855f7] font-semibold text-sm">View →</a>
                            </div>
                        @empty
                            <p class="text-[#9aa6c7] text-center py-6">No active borrows. <a href="{{ route('books.catalogue') }}" class="text-[#00d4ff]">Browse books</a></p>
                        @endforelse
                    </div>

                    <!-- Recent Browsing Activity -->
                    <div class="glass-panel p-6 rounded-lg">
                        <h2 class="text-xl font-bold text-white mb-4">Your Library Activity 🎯</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center bg-white/5 p-3 rounded-lg">
                                <span class="text-[#9aa6c7]">📖 View Library Overview</span>
                                <span class="text-xs text-[#9aa6c7]">Just now</span>
                            </div>
                            <div class="flex justify-between items-center bg-white/5 p-3 rounded-lg">
                                <span class="text-[#9aa6c7]">📚 Last Book Borrowed</span>
                                <span class="text-xs text-[#9aa6c7]">{{ auth()->user()->borrows()->latest()->first()?->created_at->diffForHumans() ?? 'Never' }}</span>
                            </div>
                            <div class="flex justify-between items-center bg-white/5 p-3 rounded-lg">
                                <span class="text-[#9aa6c7]">⭐ Member Account Active</span>
                                <span class="text-xs text-green-400 font-semibold">Active</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Quick Access & Tips -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="glass-panel p-6 rounded-lg">
                        <h2 class="text-xl font-bold text-white mb-4">Quick Actions ⚡</h2>
                        <div class="flex flex-col gap-3">
                            <a href="{{ route('books.catalogue') }}" class="py-3 px-4 bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] rounded-lg font-bold text-center hover:opacity-90 transition">
                                Browse Catalogue
                            </a>
                            <a href="{{ route('profile') }}" class="py-3 px-4 bg-white/5 text-[#9aa6c7] rounded-lg font-bold text-center hover:bg-white/10 transition">
                                Edit Profile
                            </a>
                            <a href="{{ route('borrows') }}" class="py-3 px-4 bg-white/5 text-[#9aa6c7] rounded-lg font-bold text-center hover:bg-white/10 transition">
                                My Borrows
                            </a>
                        </div>
                    </div>

                    <!-- Tips & Updates -->
                    <div class="glass-panel p-6 rounded-lg">
                        <h2 class="text-lg font-bold text-white mb-3">Pro Tips 💡</h2>
                        <ul class="space-y-2 text-sm text-[#9aa6c7]">
                            <li>✓ Explore trending books in the catalogue</li>
                            <li>✓ View your borrow history anytime</li>
                            <li>✓ Upgrade for unlimited borrowing</li>
                            <li>✓ Leave reviews to help others</li>
                            <li>✓ Manage your profile picture</li>
                        </ul>
                    </div>

                    @if(!auth()->user()->membership)
                    <!-- Membership Benefits -->
                    <div class="glass-panel p-6 rounded-lg border-l-4 border-[#00d4ff]">
                        <h2 class="text-lg font-bold text-white mb-3">Membership Benefits ✨</h2>
                        <ul class="space-y-2 text-xs text-[#9aa6c7]">
                            <li>🎁 Free book borrowing</li>
                            <li>⏱️ Extended loan periods</li>
                            <li>⭐ Priority access to new books</li>
                            <li>📞 Priority support</li>
                        </ul>
                        <button onclick="openUpgradeModal()" class="w-full mt-4 py-2 bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] rounded-lg font-bold text-sm hover:opacity-90 transition">
                            Upgrade Now
                        </button>
                    </div>
                    @endif
                </div>
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
