@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-12">
            <a href="{{ route('books.catalogue') }}" class="text-blue-600 hover:text-blue-800 font-semibold mb-4 inline-block">
                ← Back to Catalogue
            </a>
            <h1 class="text-4xl font-bold text-gray-900">{{ $genre->name }}</h1>
            @if($genre->description)
                <p class="text-lg text-gray-600 mt-2">{{ $genre->description }}</p>
            @endif
        </div>

        <!-- Books Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            @forelse($books as $book)
                <div class="group cursor-pointer">
                    <div class="relative overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-shadow duration-300">
                        <!-- Book Cover -->
                        <a href="{{ route('books.show', $book) }}">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                     alt="{{ $book->title }}" 
                                     class="w-full h-72 object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-72 bg-gradient-to-br from-indigo-400 to-pink-500 flex items-center justify-center">
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
                            <span class="text-green-600 font-semibold">${{ number_format($book->price, 2) }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-600 text-lg">No books found in this genre.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($books->count() > 0)
            <div class="flex justify-center">
                {{ $books->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
