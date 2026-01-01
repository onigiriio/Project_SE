@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Book Catalogue</h1>
            <p class="text-lg text-gray-600">Explore our curated collection of books</p>
        </div>

        <!-- Trending Books Section -->
        <section class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Trending Now</h2>
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
                            <a href="{{ route('books.show', $book) }}" class="text-lg font-semibold text-gray-900 hover:text-blue-600">
                                {{ Str::limit($book->title, 30) }}
                            </a>
                            <p class="text-gray-600 text-sm mt-1">by {{ $book->author }}</p>
                            
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
                                <span class="text-green-600 font-semibold">RM {{ number_format($book->price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-gray-600 text-center py-8">No trending books available</p>
                @endforelse
            </div>
        </section>

        <!-- Recommended Books Section -->
        <section class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Recommended For You</h2>
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
                            <a href="{{ route('books.show', $book) }}" class="text-lg font-semibold text-gray-900 hover:text-blue-600">
                                {{ Str::limit($book->title, 30) }}
                            </a>
                            <p class="text-gray-600 text-sm mt-1">by {{ $book->author }}</p>
                            
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
                                <span class="text-green-600 font-semibold">RM {{ number_format($book->price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-gray-600 text-center py-8">No recommended books available</p>
                @endforelse
            </div>
        </section>

        <!-- Browse by Genre Section -->
        <section class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Browse by Genre</h2>
            
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
    </div>
</div>
@endsection
