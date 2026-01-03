@extends('layouts.app')

@section('title', 'Edit Book — LibraryHub')

@section('content')
<div class="min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <a href="{{ route('books.show', $book) }}" class="text-blue-600 hover:text-blue-800 font-semibold mb-8 inline-block">
            ← Back to Book
        </a>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-lg p-8">
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