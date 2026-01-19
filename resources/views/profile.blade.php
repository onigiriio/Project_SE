@extends('layouts.app')

@section('title', 'My Profile — LibraryHub')

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
        <img src="/images/libraryHub-icon.svg" alt="LibraryHub" class="w-12 h-12">
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
            <button type="submit" class="w-full text-left text-red-400 hover:text-red-300 font-semibold text-sm transition py-2">Sign Out</button>
        </form>
    </div>
</aside>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <main class="space-y-6">
        <!-- Profile Header -->
        <div class="glass-panel p-6 rounded-lg">
            <div class="flex items-start gap-6">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->username }}" class="w-24 h-24 rounded-lg object-cover shadow-lg">
                @else
                    <div class="w-24 h-24 rounded-lg bg-gradient-to-br from-[#00d4ff] to-[#a855f7] flex items-center justify-center font-bold text-3xl text-[#050714]">
                        {{ strtoupper(substr(optional(auth()->user())->name ?? 'U',0,1)) }}
                    </div>
                @endif
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-white mb-2">{{ auth()->user()->name ?? auth()->user()->username }}</h1>
                    <p class="text-[#9aa6c7] mb-4">{{ auth()->user()->email }}</p>
                    <a href="#" onclick="openEditProfile()" class="px-4 py-2 bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] rounded-md font-bold text-sm hover:opacity-90 transition">
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Membership Status -->
        <div class="glass-panel p-6 rounded-lg">
            <h2 class="text-xl font-bold text-white mb-4">Membership Status</h2>
            @if(auth()->user()->membership)
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#e6eef8] font-semibold">Premium Member</p>
                        <p class="text-sm text-[#9aa6c7]">Unlimited book borrowing privileges</p>
                    </div>
                    <div class="text-4xl">✨</div>
                </div>
            @else
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#e6eef8] font-semibold">Standard Member</p>
                        <p class="text-sm text-[#9aa6c7]">Limited borrowing (Max 3 books)</p>
                    </div>
                    <button onclick="openUpgradeModal()" class="px-4 py-2 bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] rounded-md font-bold text-sm hover:opacity-90 transition">
                        Upgrade
                    </button>
                </div>
            @endif
        </div>

        <!-- Account Statistics -->
        @php
            $totalBorrowed = auth()->user()->borrows()->count();
            $activeBorrows = auth()->user()->borrows()->whereNull('returned_at')->count();
            $totalReviews = auth()->user()->reviews()->count();
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="glass-panel p-6 rounded-lg border-l-4 border-[#00d4ff]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#9aa6c7] text-sm">Member Since</p>
                        <p class="text-2xl font-bold text-white mt-2">{{ auth()->user()->created_at->format('M Y') }}</p>
                        <p class="text-xs text-[#9aa6c7] mt-1">{{ auth()->user()->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="text-4xl">📅</div>
                </div>
            </div>

            <div class="glass-panel p-6 rounded-lg border-l-4 border-[#a855f7]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#9aa6c7] text-sm">Account Type</p>
                        <p class="text-2xl font-bold text-white mt-2">{{ ucfirst(auth()->user()->user_type) }}</p>
                    </div>
                    <div class="text-4xl">{{ auth()->user()->user_type === 'librarian' ? '📖' : '👤' }}</div>
                </div>
            </div>

            <div class="glass-panel p-6 rounded-lg border-l-4 border-[#00d4ff]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#9aa6c7] text-sm">Total Borrowed</p>
                        <p class="text-2xl font-bold text-white mt-2">{{ $totalBorrowed }}</p>
                        <p class="text-xs text-[#9aa6c7] mt-1">All time</p>
                    </div>
                    <div class="text-4xl">📚</div>
                </div>
            </div>

            <div class="glass-panel p-6 rounded-lg border-l-4 border-[#a855f7]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#9aa6c7] text-sm">Active Borrows</p>
                        <p class="text-2xl font-bold text-white mt-2">{{ $activeBorrows }}</p>
                        <p class="text-xs text-[#9aa6c7] mt-1">Currently reading</p>
                    </div>
                    <div class="text-4xl">🎯</div>
                </div>
            </div>
        </div>

        <!-- Account Information -->
        <div class="glass-panel p-6 rounded-lg">
            <h2 class="text-xl font-bold text-white mb-4">Account Information</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-4 border-b border-[#9aa6c7]/10">
                    <span class="text-[#9aa6c7]">Email Address</span>
                    <span class="text-white font-semibold">{{ auth()->user()->email }}</span>
                </div>
                <div class="flex justify-between items-center pb-4 border-b border-[#9aa6c7]/10">
                    <span class="text-[#9aa6c7]">Username</span>
                    <span class="text-white font-semibold">{{ auth()->user()->username }}</span>
                </div>
                @if(auth()->user()->membership)
                    <div class="flex justify-between items-center">
                        <span class="text-[#9aa6c7]">Membership Expiry</span>
                        <span class="text-white font-semibold">{{ auth()->user()->membership_expiry ? auth()->user()->membership_expiry->format('M d, Y') : 'N/A' }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="glass-panel p-6 rounded-lg">
                <h3 class="font-bold text-white mb-3">🔍 Browse Books</h3>
                <p class="text-sm text-[#9aa6c7] mb-4">Explore our collection of books</p>
                <a href="{{ route('books.catalogue') }}" class="px-4 py-2 bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] rounded-md font-bold text-sm w-full text-center block hover:opacity-90 transition">
                    Go to Catalogue
                </a>
            </div>

            <div class="glass-panel p-6 rounded-lg">
                <h3 class="font-bold text-white mb-3">📖 Your Borrows</h3>
                <p class="text-sm text-[#9aa6c7] mb-4">View your borrowing history</p>
                <a href="{{ route('borrows') }}" class="px-4 py-2 bg-white/5 text-[#9aa6c7] rounded-md font-bold text-sm w-full text-center block hover:bg-white/10 transition">
                    View History
                </a>
            </div>

            <div class="glass-panel p-6 rounded-lg">
                <h3 class="font-bold text-white mb-3">✏️ Edit Profile</h3>
                <p class="text-sm text-[#9aa6c7] mb-4">Update your account details</p>
                <button onclick="openEditProfile()" class="px-4 py-2 bg-white/5 text-[#9aa6c7] rounded-md font-bold text-sm w-full hover:bg-white/10 transition">
                    Edit Now
                </button>
            </div>
        </div>

        <!-- Recent Borrowed Books -->
        <div class="glass-panel p-6 rounded-lg">
            <h2 class="text-xl font-bold text-white mb-4">📚 Recent Borrowed Books</h2>
            @php $recentBorrows = auth()->user()->borrows()->with('book')->latest()->take(4)->get(); @endphp
            @if($recentBorrows->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @foreach($recentBorrows as $borrow)
                        <div class="bg-white/5 p-4 rounded-lg hover:bg-white/10 transition">
                            <p class="text-white font-semibold text-sm truncate">{{ $borrow->book->title }}</p>
                            <p class="text-[#9aa6c7] text-xs mt-1">by {{ $borrow->book->author }}</p>
                            <p class="text-xs text-[#9aa6c7] mt-2">{{ $borrow->created_at->format('M d, Y') }}</p>
                            @if($borrow->returned_at)
                                <span class="inline-block mt-2 px-2 py-1 bg-green-500/20 text-green-300 text-xs rounded">Returned</span>
                            @else
                                <span class="inline-block mt-2 px-2 py-1 bg-blue-500/20 text-blue-300 text-xs rounded">Active</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-[#9aa6c7] text-center py-8">No borrow history yet. <a href="{{ route('books.catalogue') }}" class="text-[#00d4ff]">Start browsing</a></p>
            @endif
        </div>

        <!-- Reading Preferences (if reviews exist) -->
        <div class="glass-panel p-6 rounded-lg">
            <h2 class="text-xl font-bold text-white mb-4">⭐ Your Activity</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white/5 p-4 rounded-lg">
                    <p class="text-[#9aa6c7] text-sm">Total Borrowed</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $totalBorrowed }}</p>
                </div>
                <div class="bg-white/5 p-4 rounded-lg">
                    <p class="text-[#9aa6c7] text-sm">Active Borrows</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $activeBorrows }}</p>
                </div>
                <div class="bg-white/5 p-4 rounded-lg">
                    <p class="text-[#9aa6c7] text-sm">Reviews Written</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $totalReviews }}</p>
                </div>
            </div>
        </div>
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

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-[#050714] border border-white/10 rounded-lg shadow-2xl p-8 max-w-lg w-full mx-4">
        <h2 class="text-2xl font-bold text-[#e6eef8] mb-4">Edit Profile</h2>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="text-sm text-[#9aa6c7]">Profile Image</label>
                    @if(auth()->user()->avatar)
                        <div class="mt-2 mb-2">
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-20 h-20 rounded-md object-cover" alt="avatar">
                        </div>
                    @endif
                    <input type="file" name="avatar" accept="image/*" class="w-full text-sm text-white/80">
                    @error('avatar')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm text-[#9aa6c7]">Username</label>
                    <input type="text" name="username" value="{{ old('username', auth()->user()->username) }}" required class="mt-1 w-full rounded-md p-2 bg-white/3 text-black">
                    @error('username')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm text-[#9aa6c7]">Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="mt-1 w-full rounded-md p-2 bg-white/3 text-black">
                    @error('email')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm text-[#9aa6c7]">New Password (leave blank to keep current)</label>
                    <input type="password" name="password" class="mt-1 w-full rounded-md p-2 bg-white/3 text-black" autocomplete="new-password">
                    @error('password')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm text-[#9aa6c7]">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="mt-1 w-full rounded-md p-2 bg-white/3 text-black" autocomplete="new-password">
                </div>
            </div>

            <div class="mt-4 flex gap-3 justify-end">
                <button type="button" onclick="closeEditProfile()" class="px-4 py-2 border border-[#9aa6c7] text-[#9aa6c7] rounded-md">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] rounded-md font-bold">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditProfile() {
    document.getElementById('editProfileModal').classList.remove('hidden');
}
function closeEditProfile() {
    document.getElementById('editProfileModal').classList.add('hidden');
}

// Close modal on Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeEditProfile();
});

// Close when clicking outside
document.getElementById('editProfileModal')?.addEventListener('click', (e) => {
    if (e.target.id === 'editProfileModal') closeEditProfile();
});
</script>

@endsection
