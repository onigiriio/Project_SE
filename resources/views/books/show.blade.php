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
                    <div class="mb-8 pb-8 border-b border-gray-200">
                        @if(auth()->check() && auth()->user()->membership)
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-400 rounded-lg p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-sm font-semibold text-green-700 mb-1">MEMBER EXCLUSIVE</h3>
                                        <p class="text-3xl font-bold text-green-600">FREE TO BORROW</p>
                                        <p class="text-sm text-green-600 mt-2">Enjoy unlimited borrowing with your membership!</p>
                                    </div>
                                    <div class="text-5xl">✓</div>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-3">Regular price: RM {{ number_format($book->price, 2) }}</p>
                        @else
                            <div class="flex items-baseline gap-4">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-500 mb-2">Price to Purchase</h3>
                                    <p class="text-4xl font-bold text-gray-900">RM {{ number_format($book->price, 2) }}</p>
                                </div>
                                <div class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-semibold">
                                    or borrow as a member
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="mb-8">
                        @auth
                            @if(auth()->user()->user_type === 'librarian')
                                <!-- Librarian Actions -->
                                <div class="flex gap-3">
                                    <a href="{{ route('books.edit', $book) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-semibold">Edit Book</a>
                                    <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this book?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md font-semibold">Delete Book</button>
                                    </form>
                                </div>
                            @else
                                <!-- Regular User Actions -->
                                @if(isset($borrowed) && $borrowed)
                                    <button disabled class="px-4 py-2 bg-gray-400 text-white rounded-md font-semibold">Already Borrowed</button>
                                @else
                                    <button type="button" onclick="openBorrowModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-semibold">Borrow Book</button>
                                @endif
                            @endif
                        @else
                            <div class="mt-4">
                                <a href="{{ route('login') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-semibold">Log in to Borrow</a>
                            </div>
                        @endauth
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
            <style>
                textarea {
                    color: #000 !important;
                }
            </style>
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
                            <div class="flex gap-2" id="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer group star-label" data-rating="{{ $i }}">
                                        <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" {{ old('rating') == $i ? 'checked' : '' }}>
                                        <span class="text-4xl text-gray-300 peer-checked:text-yellow-400 transition star-icon">★</span>
                                    </label>
                                @endfor
                            </div>
                            @error('rating')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <script>
                                document.querySelectorAll('.star-label').forEach(label => {
                                    label.addEventListener('mouseenter', function() {
                                        const rating = parseInt(this.dataset.rating);
                                        highlightStars(rating);
                                    });
                                });
                                
                                document.getElementById('rating-stars').addEventListener('mouseleave', function() {
                                    const checked = document.querySelector('input[name="rating"]:checked');
                                    if (checked) {
                                        highlightStars(parseInt(checked.value));
                                    } else {
                                        document.querySelectorAll('.star-icon').forEach(star => {
                                            star.classList.remove('text-yellow-400');
                                            star.classList.add('text-gray-300');
                                        });
                                    }
                                });
                                
                                document.querySelectorAll('input[name="rating"]').forEach(input => {
                                    input.addEventListener('change', function() {
                                        highlightStars(parseInt(this.value));
                                    });
                                });
                                
                                function highlightStars(rating) {
                                    document.querySelectorAll('.star-icon').forEach((star, index) => {
                                        if (index < rating) {
                                            star.classList.remove('text-gray-300');
                                            star.classList.add('text-yellow-400');
                                        } else {
                                            star.classList.remove('text-yellow-400');
                                            star.classList.add('text-gray-300');
                                        }
                                    });
                                }
                                
                                // Initialize on page load
                                const checked = document.querySelector('input[name="rating"]:checked');
                                if (checked) {
                                    highlightStars(parseInt(checked.value));
                                }
                            </script>
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

<!-- Borrow Book Modal -->
<div id="borrowModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-2xl p-8 max-w-md w-full mx-4">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Book Pickup Details</h2>
        
        <form action="{{ route('books.borrow', $book) }}" method="POST">
            @csrf
            
            <!-- Pickup Date -->
            <div class="mb-6">
                <label for="pickup_date" class="block text-sm font-semibold text-gray-700 mb-2">Pickup Date *</label>
                <input type="date" id="pickup_date" name="pickup_date" 
                       min="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-black"
                       required>
            </div>

            <!-- Pickup Time -->
            <div class="mb-6">
                <label for="pickup_time" class="block text-sm font-semibold text-gray-700 mb-2">Pickup Time *</label>
                <input type="time" id="pickup_time" name="pickup_time" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-black"
                       required>
            </div>

            <!-- Borrow Duration -->
            <div class="mb-6">
                <label for="duration_days" class="block text-sm font-semibold text-gray-700 mb-2">
                    Borrowing Duration <span class="text-gray-500">(days)</span> *
                </label>
                <div class="flex items-center gap-4">
                    <input type="range" id="duration_days" name="duration_days" 
                           min="1" max="30" value="14"
                           class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                           oninput="updateDurationDisplay(this.value)">
                    <span class="text-lg font-bold text-blue-600 min-w-[3rem] text-center" id="durationDisplay">14 days</span>
                </div>
                <p class="text-xs text-gray-500 mt-2">Maximum: 30 days (1 month)</p>
            </div>

            <!-- Summary -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-700">
                    <span class="font-semibold">Book:</span> {{ $book->title }}<br>
                    <span class="font-semibold">Return by:</span> <span id="returnDate">-</span>
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="button" onclick="closeBorrowModal()" 
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition">
                    Confirm Pickup
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openBorrowModal() {
        document.getElementById('borrowModal').classList.remove('hidden');
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('pickup_date').min = today;
        document.getElementById('pickup_date').value = today;
        updateReturnDate();
    }

    function closeBorrowModal() {
        document.getElementById('borrowModal').classList.add('hidden');
    }

    function updateDurationDisplay(days) {
        const display = document.getElementById('durationDisplay');
        display.textContent = days + ' day' + (days != 1 ? 's' : '');
        updateReturnDate();
    }

    function updateReturnDate() {
        const pickupDateInput = document.getElementById('pickup_date');
        const durationInput = document.getElementById('duration_days');
        
        if (pickupDateInput.value) {
            const pickupDate = new Date(pickupDateInput.value);
            const duration = parseInt(durationInput.value);
            const returnDate = new Date(pickupDate);
            returnDate.setDate(returnDate.getDate() + duration);
            
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            document.getElementById('returnDate').textContent = returnDate.toLocaleDateString('en-US', options);
        }
    }

    // Close modal when clicking outside
    document.getElementById('borrowModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeBorrowModal();
        }
    });

    // Update return date when inputs change
    document.getElementById('pickup_date')?.addEventListener('change', updateReturnDate);
    document.getElementById('duration_days')?.addEventListener('input', updateReturnDate);

    // Initialize on page load
    window.addEventListener('load', function() {
        updateReturnDate();
    });
</script>

@endsection
