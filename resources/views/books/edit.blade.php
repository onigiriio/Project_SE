@extends('layouts.app')

@section('title', 'Edit Book — LibraryHub')

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
        display: block;
    }

    .nav-link:hover {
        background: linear-gradient(90deg, rgba(0, 212, 255, 0.04), rgba(168, 85, 247, 0.03));
        color: #e6eef8;
    }
</style>

<!-- Sidebar Panel -->
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
        @if(auth()->user()->user_type === 'librarian')
            <a href="{{ route('librarian.dashboard') }}" class="nav-link">Overview</a>
            <a href="{{ route('books.catalogue') }}" class="nav-link">Manage Books</a>
            <a href="{{ route('librarian.users') }}" class="nav-link">Manage Users</a>
        @else
            <a href="{{ route('dashboard') }}" class="nav-link">Overview</a>
            <a href="{{ route('profile') }}" class="nav-link">My Profile</a>
            <a href="{{ route('books.catalogue') }}" class="nav-link">Book Catalogue</a>
            <a href="{{ route('borrows') }}" class="nav-link">My Borrows</a>
        @endif
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

        @auth
        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="w-full text-left text-red-400 hover:text-red-300 font-semibold text-sm transition py-2">Sign Out</button>
        </form>
        @endauth
    </div>
</aside>

<div class="min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <a href="{{ route('books.show', $book) }}" class="text-blue-600 hover:text-blue-800 font-semibold mb-8 inline-block">
            ← Back to Book
        </a>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <style>
                input[type="text"],
                input[type="email"],
                input[type="password"],
                input[type="date"],
                input[type="number"],
                input[type="file"],
                textarea,
                select {
                    color: #000 !important;
                }
            </style>
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Edit Book: {{ $book->title }}</h1>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="text-red-700 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $book->title) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Author -->
                    <div>
                        <label for="author" class="block text-sm font-semibold text-gray-700 mb-2">Author *</label>
                        <input type="text" id="author" name="author" value="{{ old('author', $book->author) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('author')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ISBN -->
                    <div>
                        <label for="isbn" class="block text-sm font-semibold text-gray-700 mb-2">ISBN *</label>
                        <input type="text" id="isbn" name="isbn" value="{{ old('isbn', $book->isbn) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('isbn')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Publisher -->
                    <div>
                        <label for="publisher" class="block text-sm font-semibold text-gray-700 mb-2">Publisher *</label>
                        <input type="text" id="publisher" name="publisher" value="{{ old('publisher', $book->publisher) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('publisher')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Published Date -->
                    <div>
                        <label for="published_date" class="block text-sm font-semibold text-gray-700 mb-2">Published Date *</label>
                        <input type="date" id="published_date" name="published_date" value="{{ old('published_date', $book->published_date->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('published_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pages -->
                    <div>
                        <label for="pages" class="block text-sm font-semibold text-gray-700 mb-2">Pages *</label>
                        <input type="number" id="pages" name="pages" value="{{ old('pages', $book->pages) }}" min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('pages')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">Price (RM) *</label>
                        <input type="number" id="price" name="price" value="{{ old('price', $book->price) }}" min="0" step="0.01"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('price')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Cover Image -->
                    @if($book->cover_image)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Current Cover Image</label>
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Current cover" class="w-32 h-48 object-cover rounded-lg shadow-md">
                        </div>
                    @endif

                    <!-- Cover Image -->
                    <div class="md:col-span-2">
                        <label for="cover_image" class="block text-sm font-semibold text-gray-700 mb-2">Change Cover Image</label>
                        <input type="file" id="cover_image" name="cover_image" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-gray-500 text-sm mt-1">Leave empty to keep current image. Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB</p>
                        @error('cover_image')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Genres -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Genres</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($genres as $genre)
                                <label class="flex items-center">
                                    <input type="checkbox" name="genre_ids[]" value="{{ $genre->id }}"
                                           {{ in_array($genre->id, old('genre_ids', $book->genres->pluck('id')->toArray())) ? 'checked' : '' }}
                                           class="mr-2 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">{{ $genre->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('genre_ids')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
                        <textarea id="description" name="description" rows="6"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  required>{{ old('description', $book->description) }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end gap-4">
                    <a href="{{ route('books.show', $book) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition">
                        Update Book
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection