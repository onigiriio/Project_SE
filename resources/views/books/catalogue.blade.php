@extends('layouts.app')

@section('content')
<style>
    .sidebar-panel {
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
        @if(auth()->user()->user_type === 'librarian')
            <a href="{{ route('librarian.dashboard') }}" class="nav-link">Overview</a>
            <a href="{{ route('books.catalogue') }}" class="inline-block text-sm text-[#e6eef8] bg-gradient-to-r from-[#002b33]/10 to-[#3a003f]/6 px-3 py-2 rounded-md font-semibold">Manage Books</a>
            <a href="{{ route('librarian.users') }}" class="nav-link">Manage Users</a>
        @else
            <a href="{{ route('dashboard') }}" class="nav-link">Overview</a>
            <a href="{{ route('profile') }}" class="nav-link">My Profile</a>
            <a href="{{ route('books.catalogue') }}" class="inline-block text-sm text-[#e6eef8] bg-gradient-to-r from-[#002b33]/10 to-[#3a003f]/6 px-3 py-2 rounded-md font-semibold">Book Catalogue</a>
            <a href="{{ route('borrows') }}" class="nav-link">My Borrows</a>
        @endif
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

<div class="min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-white mb-4">Book Catalogue</h1>
            <p class="text-lg text-gray-300">Explore our curated collection of books</p>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('books.index') }}" class="mb-6">
            <div class="flex gap-3 items-center">
                        <div class="relative flex-1">
                    <input aria-label="Search books" type="search" name="q" value="{{ old('q', request('q')) }}" class="w-full rounded-md border border-[#9aa6c7]/30 px-4 py-3 bg-white/5 text-white placeholder-[#9aa6c7] focus:outline-none focus:ring-2 focus:ring-[#00d4ff]" placeholder="Search by title, author or description">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[#9aa6c7]">🔍</span>
                </div>
                <button class="px-4 py-3 bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] rounded-md font-semibold shadow hover:opacity-95 transition" type="submit">Search</button>
                @if(request('q'))
                    <a href="{{ route('books.index') }}" class="px-4 py-3 rounded-md bg-white/5 text-[#9aa6c7] border border-[#9aa6c7]/30 hover:bg-white/10 transition">Clear</a>
                @endif
            </div>
        </form>

        @if($books->total() === 0)
            <div class="alert alert-info">No books found{{ request('q') ? " for \"".e(request('q'))."\"" : '' }}.</div>
        @endif

        <!-- Add Book Button (Librarians Only) -->
        @if(auth()->user()->user_type === 'librarian')
        <div class="mb-6">
            <button onclick="openAddBookModal()" class="px-6 py-3 bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] rounded-md font-semibold hover:opacity-95 transition shadow-lg">
                ➕ Add New Book
            </button>
        </div>
        @endif

        <!-- Section Switcher Buttons -->
        <div class="mb-6">
            <div class="inline-flex items-center gap-2 bg-transparent p-2 rounded-md">
                <button id="all-btn" aria-pressed="true" class="px-4 py-2 rounded-md bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-white font-semibold shadow-sm hover:opacity-95 transition section-btn">All Books</button>
                <button id="trending-btn" aria-pressed="false" class="px-4 py-2 rounded-md bg-gradient-to-r from-[#00d4ff]/20 to-[#a855f7]/20 text-white border border-[#00d4ff]/50 hover:from-[#00d4ff]/30 hover:to-[#a855f7]/30 transition section-btn">Trending Now</button>
                <button id="recommended-btn" aria-pressed="false" class="px-4 py-2 rounded-md bg-gradient-to-r from-[#00d4ff]/20 to-[#a855f7]/20 text-white border border-[#00d4ff]/50 hover:from-[#00d4ff]/30 hover:to-[#a855f7]/30 transition section-btn">Recommended</button>
                <button id="browse-btn" aria-pressed="false" class="px-4 py-2 rounded-md bg-gradient-to-r from-[#00d4ff]/20 to-[#a855f7]/20 text-white border border-[#00d4ff]/50 hover:from-[#00d4ff]/30 hover:to-[#a855f7]/30 transition section-btn">Browse by Genre</button>
            </div>
        </div>

        <div id="sections-wrapper">
            <!-- Trending Books Section -->
            <section id="trending-section" class="mb-16" style="display:none;">
                <div class="flex justify-between items-center">
                    <h2 class="text-3xl font-bold text-white mb-6">Trending Now</h2>
                    <button id="back-from-trending" class="px-3 py-2 rounded-md bg-transparent text-white border border-white/10 hover:bg-white/5 transition">Back to All Books</button>
                </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($trendingBooks as $book)
                    <div class="group cursor-pointer">
                        <div class="relative overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-shadow duration-300">
                            <!-- Book Cover -->
                            <a href="{{ route('books.show', $book) }}">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                         alt="{{ $book->title }}" 
                                         class="w-full h-72 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-72 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                        <div class="text-white text-center p-4">
                                            <div class="text-6xl mb-2">📚</div>
                                            <p class="font-semibold">{{ $book->title }}</p>
                                        </div>
                                    </div>
                                @endif
                            </a>

                            <!-- Trending Badge -->
                            <div class="absolute top-2 right-2 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                Trending
                            </div>
                        </div>

                        <!-- Book Info -->
                        <div class="mt-4">
                            <a href="{{ route('books.show', $book) }}" class="text-lg font-semibold text-white hover:text-blue-400">
                                {{ Str::limit($book->title, 30) }}
                            </a>
                            <p class="text-gray-300 text-sm mt-1">by {{ $book->author }}</p>
                            
                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400">
                                        @for($i = 0; $i < 5; $i++)
                                            @if($i < round($book->rating))
                                                <span>★</span>
                                            @else
                                                <span class="text-gray-300">★</span>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-gray-600 text-sm ml-2">({{ $book->rating_count }})</span>
                                </div>
                                <div class="text-sm font-bold text-green-400">
                                    @if(auth()->check() && auth()->user()->membership)
                                        FREE
                                    @else
                                        RM {{ number_format($book->price, 2) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-gray-600 text-center py-8">No trending books available</p>
                @endforelse
            </div>
        </section>

        <!-- Recommended Books Section -->
        <section id="recommended-section" class="mb-16" style="display:none;">
            <div class="flex justify-between items-center">
                <h2 class="text-3xl font-bold text-white mb-6">Recommended For You</h2>
                <button id="back-from-recommended" class="px-3 py-2 rounded-md bg-transparent text-white border border-white/10 hover:bg-white/5 transition">Back to All Books</button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($recommendedBooks as $book)
                    <div class="group cursor-pointer">
                        <div class="relative overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-shadow duration-300">
                            <!-- Book Cover -->
                            <a href="{{ route('books.show', $book) }}">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                         alt="{{ $book->title }}" 
                                         class="w-full h-72 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-72 bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                                        <div class="text-white text-center p-4">
                                            <div class="text-6xl mb-2">📚</div>
                                            <p class="font-semibold">{{ $book->title }}</p>
                                        </div>
                                    </div>
                                @endif
                            </a>

                            <!-- Recommended Badge -->
                            <div class="absolute top-2 right-2 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                Recommended
                            </div>
                        </div>

                        <!-- Book Info -->
                        <div class="mt-4">
                            <a href="{{ route('books.show', $book) }}" class="text-lg font-semibold text-white hover:text-blue-400">
                                {{ Str::limit($book->title, 30) }}
                            </a>
                            <p class="text-gray-300 text-sm mt-1">by {{ $book->author }}</p>
                            
                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400">
                                        @for($i = 0; $i < 5; $i++)
                                            @if($i < round($book->rating))
                                                <span>★</span>
                                            @else
                                                <span class="text-gray-300">★</span>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-gray-600 text-sm ml-2">({{ $book->rating_count }})</span>
                                </div>
                                <div class="text-sm font-bold text-green-400">
                                    @if(auth()->check() && auth()->user()->membership)
                                        FREE
                                    @else
                                        RM {{ number_format($book->price, 2) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-gray-600 text-center py-8">No recommended books available</p>
                @endforelse
            </div>
        </section>

        <!-- Browse by Genre Section -->
        <section id="browse-section" class="mb-16" style="display:none;">
            <div class="flex justify-between items-center">
                <h2 class="text-3xl font-bold text-white mb-6">Browse by Genre</h2>
                <button id="back-from-browse" class="px-3 py-2 rounded-md bg-transparent text-white border border-white/10 hover:bg-white/5 transition">Back to All Books</button>
            </div>
            
            @if($genres->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($genres as $genre)
                        <a href="{{ route('books.by-genre', $genre) }}" 
                           class="group bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 p-6 text-center">
                            
                            <!-- Genre Icon -->
                            <div class="mb-4 flex justify-center">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white text-3xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    📚
                                </div>
                            </div>
                            
                            <!-- Genre Name -->
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition">
                                {{ $genre->name }}
                            </h3>
                            
                            <!-- Genre Description -->
                            @if($genre->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ $genre->description }}
                                </p>
                            @endif
                            
                            <!-- Book Count -->
                            <div class="pt-4 border-t border-gray-200">
                                <span class="text-blue-600 font-semibold text-sm">
                                    {{ $genre->books->count() }} {{ Str::plural('book', $genre->books->count()) }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600 text-center py-12">No genres available</p>
            @endif
        </section>

        <!-- Books Listing Section -->
        <section>
            <h2 class="text-3xl font-bold text-white mb-6">All Books</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($books as $book)
                    <div class="group cursor-pointer">
                        <div class="relative overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-shadow duration-300">
                            <!-- Book Cover -->
                            <a href="{{ route('books.show', $book) }}">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                         alt="{{ $book->title }}" 
                                         class="w-full h-72 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-72 bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center">
                                        <div class="text-white text-center p-4">
                                            <div class="text-6xl mb-2">📚</div>
                                            <p class="font-semibold">{{ $book->title }}</p>
                                        </div>
                                    </div>
                                @endif
                            </a>
                        </div>

                        <!-- Book Info -->
                        <div class="mt-4">
                            <a href="{{ route('books.show', $book) }}" class="text-lg font-semibold text-white hover:text-blue-400">
                                {{ Str::limit($book->title, 30) }}
                            </a>
                            <p class="text-gray-300 text-sm mt-1">by {{ $book->author }}</p>
                            
                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400">
                                        @for($i = 0; $i < 5; $i++)
                                            @if($i < round($book->rating))
                                                <span>★</span>
                                            @else
                                                <span class="text-gray-300">★</span>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-gray-600 text-sm ml-2">({{ $book->rating_count }})</span>
                                </div>
                                <div class="text-sm font-bold text-green-400">
                                    @if(auth()->check() && auth()->user()->membership)
                                        FREE
                                    @else
                                        RM {{ number_format($book->price, 2) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $books->links() }}
            </div>
        </section>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sections = {
        all: document.getElementById('all-section') || document.getElementById('all-section'),
        trending: document.getElementById('trending-section'),
        recommended: document.getElementById('recommended-section'),
        browse: document.getElementById('browse-section'),
    };
    const buttons = {
        all: document.getElementById('all-btn'),
        trending: document.getElementById('trending-btn'),
        recommended: document.getElementById('recommended-btn'),
        browse: document.getElementById('browse-btn'),
    };

    function show(name) {
        Object.keys(sections).forEach(k => {
            const el = sections[k];
            if (!el) return;
            el.style.display = (k === name) ? '' : 'none';
        });

        Object.keys(buttons).forEach(k => {
            const b = buttons[k];
            if (!b) return;

            if (k === name) {
                b.setAttribute('aria-pressed', 'true');
                b.classList.remove('bg-white','text-gray-900','border','border-gray-200');
                b.classList.add('bg-gradient-to-r','from-blue-500','to-purple-600','text-white','shadow-sm');
            } else {
                b.setAttribute('aria-pressed', 'false');
                b.classList.remove('bg-gradient-to-r','from-blue-500','to-purple-600','text-white','shadow-sm');
                b.classList.add('bg-white','text-gray-900','border','border-gray-200');
            }
        });

        const el = sections[name];
        if (el) el.scrollIntoView({behavior:'smooth', block:'start'});
    }

    buttons.all && buttons.all.addEventListener('click', () => show('all'));
    buttons.trending && buttons.trending.addEventListener('click', () => show('trending'));
    buttons.recommended && buttons.recommended.addEventListener('click', () => show('recommended'));
    buttons.browse && buttons.browse.addEventListener('click', () => show('browse'));

    document.getElementById('back-from-trending')?.addEventListener('click', () => show('all'));
    document.getElementById('back-from-recommended')?.addEventListener('click', () => show('all'));
    document.getElementById('back-from-browse')?.addEventListener('click', () => show('all'));

    // If 'section' query param present, show that section (e.g., ?section=trending)
    try {
        const params = new URLSearchParams(window.location.search);
        const sectionParam = params.get('section');
        if (sectionParam && sections[sectionParam]) {
            show(sectionParam);
        } else {
            show('all');
        }
    } catch (e) {
        show('all');
    }
});
</script>

<!-- Add Book Modal -->
@if(auth()->user()->user_type === 'librarian')
<div id="addBookModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-[#050714] border border-[#9aa6c7]/20 rounded-lg shadow-2xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white">Add New Book</h2>
            <button onclick="closeAddBookModal()" class="text-[#9aa6c7] hover:text-white text-2xl">×</button>
        </div>

        <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-4">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-semibold text-[#e6eef8] mb-2">Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="w-full px-4 py-2 border border-[#9aa6c7]/30 rounded-lg bg-white/5 text-white placeholder-[#9aa6c7] focus:outline-none focus:ring-2 focus:ring-[#00d4ff]"
                           required>
                </div>

                <!-- Author & ISBN -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#e6eef8] mb-2">Author *</label>
                        <input type="text" name="author" value="{{ old('author') }}"
                               class="w-full px-4 py-2 border border-[#9aa6c7]/30 rounded-lg bg-white/5 text-white placeholder-[#9aa6c7] focus:outline-none focus:ring-2 focus:ring-[#00d4ff]"
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#e6eef8] mb-2">ISBN *</label>
                        <input type="text" name="isbn" value="{{ old('isbn') }}"
                               class="w-full px-4 py-2 border border-[#9aa6c7]/30 rounded-lg bg-white/5 text-white placeholder-[#9aa6c7] focus:outline-none focus:ring-2 focus:ring-[#00d4ff]"
                               required>
                    </div>
                </div>

                <!-- Publisher & Published Date -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#e6eef8] mb-2">Publisher *</label>
                        <input type="text" name="publisher" value="{{ old('publisher') }}"
                               class="w-full px-4 py-2 border border-[#9aa6c7]/30 rounded-lg bg-white/5 text-white placeholder-[#9aa6c7] focus:outline-none focus:ring-2 focus:ring-[#00d4ff]"
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#e6eef8] mb-2">Published Date *</label>
                        <input type="date" name="published_date" value="{{ old('published_date') }}"
                               class="w-full px-4 py-2 border border-[#9aa6c7]/30 rounded-lg bg-white/5 text-white focus:outline-none focus:ring-2 focus:ring-[#00d4ff]"
                               required>
                    </div>
                </div>

                <!-- Pages -->
                <div>
                    <label class="block text-sm font-semibold text-[#e6eef8] mb-2">Pages *</label>
                    <input type="number" name="pages" value="{{ old('pages') }}" min="1"
                           class="w-full px-4 py-2 border border-[#9aa6c7]/30 rounded-lg bg-white/5 text-white placeholder-[#9aa6c7] focus:outline-none focus:ring-2 focus:ring-[#00d4ff]"
                           required>
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-sm font-semibold text-[#e6eef8] mb-2">Price (RM) *</label>
                    <input type="number" name="price" value="{{ old('price') }}" min="0" step="0.01"
                           class="w-full px-4 py-2 border border-[#9aa6c7]/30 rounded-lg bg-white/5 text-white placeholder-[#9aa6c7] focus:outline-none focus:ring-2 focus:ring-[#00d4ff]"
                           required>
                </div>

                <!-- Cover Image -->
                <div>
                    <label class="block text-sm font-semibold text-[#e6eef8] mb-2">Cover Image</label>
                    <input type="file" name="cover_image" accept="image/*"
                           class="w-full px-4 py-2 border border-[#9aa6c7]/30 rounded-lg bg-white/5 text-[#9aa6c7] focus:outline-none focus:ring-2 focus:ring-[#00d4ff]">
                    <p class="text-xs text-[#9aa6c7] mt-1">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB</p>
                </div>

                <!-- Genres -->
                <div>
                    <label class="block text-sm font-semibold text-[#e6eef8] mb-2">Genres</label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($genres as $genre)
                            <label class="flex items-center">
                                <input type="checkbox" name="genre_ids[]" value="{{ $genre->id }}"
                                       {{ in_array($genre->id, old('genre_ids', [])) ? 'checked' : '' }}
                                       class="mr-2">
                                <span class="text-sm text-[#9aa6c7]">{{ $genre->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-[#e6eef8] mb-2">Description *</label>
                    <textarea name="description" rows="5"
                              class="w-full px-4 py-2 border border-[#9aa6c7]/30 rounded-lg bg-white/5 text-white placeholder-[#9aa6c7] focus:outline-none focus:ring-2 focus:ring-[#00d4ff]"
                              required>{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeAddBookModal()" class="flex-1 px-4 py-3 rounded-lg bg-white/5 text-[#9aa6c7] border border-[#9aa6c7]/30 hover:bg-white/10 transition">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] rounded-lg font-semibold hover:opacity-95 transition">
                    Add Book
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddBookModal() {
    document.getElementById('addBookModal').classList.remove('hidden');
}

function closeAddBookModal() {
    document.getElementById('addBookModal').classList.add('hidden');
}

document.getElementById('addBookModal')?.addEventListener('click', (e) => {
    if (e.target.id === 'addBookModal') {
        closeAddBookModal();
    }
});
</script>
@endif

@endsection
