@extends('layouts.app')

@section('title', 'Add New Book — LibraryHub')

@section('content')
<div class="min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <a href="{{ route('books.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold mb-8 inline-block">
            ← Back to Books
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
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Add New Book</h1>

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

            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Author -->
                    <div>
                        <label for="author" class="block text-sm font-semibold text-gray-700 mb-2">Author *</label>
                        <input type="text" id="author" name="author" value="{{ old('author') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('author')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ISBN -->
                    <div>
                        <label for="isbn" class="block text-sm font-semibold text-gray-700 mb-2">ISBN *</label>
                        <input type="text" id="isbn" name="isbn" value="{{ old('isbn') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('isbn')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Publisher -->
                    <div>
                        <label for="publisher" class="block text-sm font-semibold text-gray-700 mb-2">Publisher *</label>
                        <input type="text" id="publisher" name="publisher" value="{{ old('publisher') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('publisher')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Published Date -->
                    <div>
                        <label for="published_date" class="block text-sm font-semibold text-gray-700 mb-2">Published Date *</label>
                        <input type="date" id="published_date" name="published_date" value="{{ old('published_date') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('published_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pages -->
                    <div>
                        <label for="pages" class="block text-sm font-semibold text-gray-700 mb-2">Pages *</label>
                        <input type="number" id="pages" name="pages" value="{{ old('pages') }}" min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('pages')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cover Image -->
                    <div class="md:col-span-2">
                        <label for="cover_image" class="block text-sm font-semibold text-gray-700 mb-2">Cover Image</label>
                        <input type="file" id="cover_image" name="cover_image" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-gray-500 text-sm mt-1">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB</p>
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
                                           {{ in_array($genre->id, old('genre_ids', [])) ? 'checked' : '' }}
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
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition">
                        Create Book
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection