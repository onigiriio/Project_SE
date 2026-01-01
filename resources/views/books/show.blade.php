@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <a href="{{ route('books.catalogue') }}" class="text-blue-600 hover:text-blue-800 font-semibold mb-8 inline-block">
            ← Back to Catalogue
        </a>

        <!-- Book Details Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Book Cover -->
                <div class="md:col-span-1">
                    @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" 
                             alt="{{ $book->title }}" 
                             class="w-full rounded-lg shadow-lg">
                    @else
                        <div class="w-full aspect-square bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center">
                            <div class="text-white text-center">
                                <div class="text-8xl mb-4">📚</div>
                                <p class="text-2xl font-semibold">{{ $book->title }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Genre Tags -->
                    <div class="mt-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Genres</h3>
                        <div class="flex flex-wrap gap-2">
                            @forelse($book->genres as $genre)
                                <a href="{{ route('books.by-genre', $genre) }}" 
                                   class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm hover:bg-blue-200 transition">
                                    {{ $genre->name }}
                                </a>
                            @empty
                                <p class="text-gray-500 text-sm">No genres assigned</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Book Information -->
                <div class="md:col-span-2">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $book->title }}</h1>
                    <p class="text-xl text-gray-600 mb-6">by <span class="font-semibold">{{ $book->author }}</span></p>

                    <!-- Rating Section -->
                    <div class="flex items-center mb-8 pb-8 border-b border-gray-200">
                        <div class="flex items-center mr-6">
                            <div class="flex text-yellow-400 text-3xl">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < round($book->rating))
                                        <span>★</span>
                                    @else
                                        <span class="text-gray-300">★</span>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-gray-700 font-semibold ml-3">
                                {{ number_format($book->rating, 1) }} 
                                <span class="text-gray-500">({{ $book->rating_count }} {{ Str::plural('review', $book->rating_count) }})</span>
                            </span>
                        </div>
                        <div class="text-gray-500">
                            <span>👁 {{ number_format($book->view_count) }} views</span>
                        </div>
                    </div>

                    <!-- Book Metadata -->
                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 mb-2">ISBN</h3>
                            <p class="text-gray-900">{{ $book->isbn }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 mb-2">Pages</h3>
                            <p class="text-gray-900">{{ $book->pages }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 mb-2">Publisher</h3>
                            <p class="text-gray-900">{{ $book->publisher }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 mb-2">Published Date</h3>
                            <p class="text-gray-900">{{ $book->published_date->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <!-- Price Section -->
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-6 mb-8">
                        <p class="text-gray-600 text-sm mb-2">Price</p>
                        <p class="text-4xl font-bold text-green-600">RM {{ number_format($book->price, 2) }}</p>
                    </div>

                    <!-- Description -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Description</h2>
                        <p class="text-gray-700 leading-relaxed">{{ $book->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Reviews</h2>

            @auth
                <!-- Review Form -->
                <div class="mb-12 p-6 bg-gray-50 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Share Your Review</h3>
                    
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <ul class="text-red-700 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('books.store-review', $book) }}" method="POST">
                        @csrf

                        <!-- Rating -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Your Rating</label>
                            <div class="flex gap-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" {{ old('rating') == $i ? 'checked' : '' }}>
                                        <span class="text-4xl text-gray-300 group-hover:text-yellow-400 peer-checked:text-yellow-400 transition">★</span>
                                    </label>
                                @endfor
                            </div>
                            @error('rating')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Comment -->
                        <div class="mb-6">
                            <label for="comment" class="block text-sm font-semibold text-gray-700 mb-2">Your Review (Optional)</label>
                            <textarea id="comment" name="comment" rows="4" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Share your thoughts about this book...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                            Submit Review
                        </button>
                    </form>
                </div>
            @else
                <p class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-lg text-blue-700">
                    <a href="{{ route('login') }}" class="font-semibold hover:underline">Log in</a> to leave a review.
                </p>
            @endauth

            <!-- Reviews List -->
            <div class="space-y-8">
                @forelse($reviews as $review)
                    <div class="pb-8 border-b border-gray-200 last:border-b-0">
                        <!-- Reviewer Info -->
                        <div class="flex items-start mb-3">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">{{ $review->user->username }}</h4>
                                <p class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                            
                            <!-- Rating Stars -->
                            <div class="flex text-yellow-400">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < $review->rating)
                                        <span>★</span>
                                    @else
                                        <span class="text-gray-300">★</span>
                                    @endif
                                @endfor
                            </div>
                        </div>

                        <!-- Review Comment -->
                        @if($review->comment)
                            <p class="text-gray-700 leading-relaxed mb-4">{{ $review->comment }}</p>
                        @endif

                        <!-- Helpful Button -->
                        <button class="text-sm text-gray-600 hover:text-gray-900">
                            👍 Helpful ({{ $review->helpful_count }})
                        </button>
                    </div>
                @empty
                    <p class="text-gray-600 text-center py-8">No reviews yet. Be the first to review this book!</p>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($reviews->count() > 0)
                <div class="mt-8">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
