@extends('layouts.app')

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
        <img src="/images/libraryHub-icon.svg" alt="IIUM Library Management System" class="w-12 h-12">
        <div>
            <div class="font-semibold">IIUM Library Management System</div>
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <a href="{{ route('books.catalogue') }}" class="text-[#00d4ff] hover:text-[#a855f7] font-semibold mb-8 inline-block transition">
            ← Back to Catalogue
        </a>

        <!-- Book Details Section -->
        <div class="glass-panel rounded-lg p-8 mb-12">
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
                        <h3 class="text-sm font-semibold text-[#9aa6c7] mb-2">Genres</h3>
                        <div class="flex flex-wrap gap-2">
                            @forelse($book->genres as $genre)
                                <a href="{{ route('books.by-genre', $genre) }}" 
                                   class="inline-block bg-gradient-to-r from-[#00d4ff]/20 to-[#a855f7]/20 text-[#00d4ff] px-3 py-1 rounded-full text-sm hover:from-[#00d4ff]/30 hover:to-[#a855f7]/30 transition border border-[#00d4ff]/30">
                                    {{ $genre->name }}
                                </a>
                            @empty
                                <p class="text-[#9aa6c7] text-sm">No genres assigned</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Book Information -->
                <div class="md:col-span-2">
                    <h1 class="text-4xl font-bold text-white mb-2">{{ $book->title }}</h1>
                    <p class="text-xl text-[#9aa6c7] mb-6">by <span class="font-semibold text-[#e6eef8]">{{ $book->author }}</span></p>

                    <!-- Rating Section -->
                    <div class="flex items-center mb-8 pb-8 border-b border-[#9aa6c7]/10">
                        <div class="flex items-center mr-6">
                            <div class="flex text-yellow-400 text-3xl">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < round($book->rating))
                                        <span>★</span>
                                    @else
                                        <span class="text-[#9aa6c7]/30">★</span>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-white font-semibold ml-3">
                                {{ number_format($book->rating, 1) }} 
                                <span class="text-[#9aa6c7]">({{ $book->rating_count }} {{ Str::plural('review', $book->rating_count) }})</span>
                            </span>
                        </div>
                        <div class="text-[#9aa6c7]">
                            <span>👁 {{ number_format($book->view_count) }} views</span>
                        </div>
                    </div>

                    <!-- Book Metadata -->
                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-sm font-semibold text-[#9aa6c7] mb-2">ISBN</h3>
                            <p class="text-white">{{ $book->isbn }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-[#9aa6c7] mb-2">Pages</h3>
                            <p class="text-white">{{ $book->pages }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-[#9aa6c7] mb-2">Publisher</h3>
                            <p class="text-white">{{ $book->publisher }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-[#9aa6c7] mb-2">Published Date</h3>
                            <p class="text-white">{{ $book->published_date->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <!-- Price Section -->
                    <div class="mb-8 pb-8 border-b border-[#9aa6c7]/10">
                        @if(auth()->check() && auth()->user()->membership)
                            <div class="bg-gradient-to-r from-[#00d4ff]/10 to-[#a855f7]/10 border-2 border-[#00d4ff]/50 rounded-lg p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-sm font-semibold text-[#00d4ff] mb-1">MEMBER EXCLUSIVE</h3>
                                        <p class="text-3xl font-bold text-[#a855f7]">FREE TO BORROW</p>
                                        <p class="text-sm text-[#9aa6c7] mt-2">Enjoy unlimited borrowing with your membership!</p>
                                    </div>
                                    <div class="text-5xl">✓</div>
                                </div>
                            </div>
                            <p class="text-xs text-[#9aa6c7] mt-3">Regular price: RM {{ number_format($book->price, 2) }}</p>
                        @else
                            <div class="flex items-baseline gap-4">
                                <div>
                                    <h3 class="text-sm font-semibold text-[#9aa6c7] mb-2">Price to Purchase</h3>
                                    <p class="text-4xl font-bold text-green-400">RM {{ number_format($book->price, 2) }}</p>
                                </div>
                                <div class="bg-gradient-to-r from-[#00d4ff]/20 to-[#a855f7]/20 text-[#00d4ff] px-4 py-2 rounded-lg text-sm font-semibold border border-[#00d4ff]/30">
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
                                    <a href="{{ route('books.edit', $book) }}" class="px-4 py-2 bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] rounded-md font-semibold hover:opacity-90 transition">Edit Book</a>
                                    <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this book?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md font-semibold transition">Delete Book</button>
                                    </form>
                                </div>
                            @else
                                <!-- Regular User Actions -->
                                @if(isset($borrowed) && $borrowed)
                                    <button disabled class="px-4 py-2 bg-[#9aa6c7]/30 text-[#9aa6c7] rounded-md font-semibold cursor-not-allowed">Already Borrowed</button>
                                @else
                                    <button type="button" onclick="openBorrowModal()" class="px-4 py-2 bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] rounded-md font-semibold hover:opacity-90 transition">Borrow Book</button>
                                @endif
                            @endif
                        @else
                            <div class="mt-4">
                                <a href="{{ route('login') }}" class="px-4 py-2 bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] rounded-md font-semibold hover:opacity-90 transition inline-block">Log in to Borrow</a>
                            </div>
                        @endauth
                    </div>

                    <!-- Description -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-white mb-4">Description</h2>
                        <p class="text-[#9aa6c7] leading-relaxed">{{ $book->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="glass-panel rounded-lg p-8">
            <style>
                textarea {
                    color: #e6eef8 !important;
                }
            </style>
            <h2 class="text-2xl font-bold text-white mb-8">Reviews</h2>

            @auth
                <!-- Review Form -->
                <div class="mb-12 p-6 bg-white/5 rounded-lg border border-[#9aa6c7]/10">
                    <h3 class="text-lg font-semibold text-white mb-4">Share Your Review</h3>
                    
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
                            <label class="block text-sm font-semibold text-white mb-2">Your Rating</label>
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
                            <label for="comment" class="block text-sm font-semibold text-white mb-2">Your Review (Optional)</label>
                            <textarea id="comment" name="comment" rows="4" 
                                      class="w-full px-4 py-2 border border-[#9aa6c7]/30 rounded-lg bg-white/5 text-white placeholder-[#9aa6c7] focus:outline-none focus:ring-2 focus:ring-[#00d4ff]"
                                      placeholder="Share your thoughts about this book...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="bg-gradient-to-r from-[#00d4ff] to-[#a855f7] text-[#050714] font-semibold py-2 px-6 rounded-lg hover:opacity-90 transition">
                            Submit Review
                        </button>
                    </form>
                </div>
            @else
                <p class="mb-8 p-4 bg-[#00d4ff]/10 border border-[#00d4ff]/30 rounded-lg text-[#00d4ff]">
                    <a href="{{ route('login') }}" class="font-semibold hover:text-[#a855f7] transition">Log in</a> to leave a review.
                </p>
            @endauth

            <!-- Reviews List -->
            <div class="space-y-8">
                @forelse($reviews as $review)
                    <div class="pb-8 border-b border-[#9aa6c7]/10 last:border-b-0" id="review-{{ $review->id }}">
                        <!-- Reviewer Info -->
                        <div class="flex items-start mb-3">
                            <div class="flex-1">
                                <h4 class="font-semibold text-white">{{ $review->user->username }}</h4>
                                <p class="text-sm text-[#9aa6c7]">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                            
                            <!-- Delete Button for Librarians -->
                            @if(Auth::check() && Auth::user()->user_type === 'librarian')
                                <button type="button" onclick="deleteReview({{ $review->id }})" class="ml-4 p-2 text-[#9aa6c7] hover:text-red-400 transition" title="Delete review">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @endif
                            
                            <!-- Rating Stars -->
                            <div class="flex text-yellow-400">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < $review->rating)
                                        <span>★</span>
                                    @else
                                        <span class="text-[#9aa6c7]/30">★</span>
                                    @endif
                                @endfor
                            </div>
                        </div>

                        <!-- Review Comment -->
                        @if($review->comment)
                            <p class="text-[#e6eef8] leading-relaxed mb-4">{{ $review->comment }}</p>
                        @endif

                        <!-- Helpful Button -->
                        <button type="button" onclick="markHelpful({{ $review->id }})" class="helpful-btn text-sm text-[#9aa6c7] hover:text-[#00d4ff] transition" data-review-id="{{ $review->id }}">
                            👍 Helpful (<span class="helpful-count">{{ $review->helpful_count }}</span>)
                        </button>
                    </div>
                @empty
                    <p class="text-[#9aa6c7] text-center py-8">No reviews yet. Be the first to review this book!</p>
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

    // Mark review as helpful
    function markHelpful(reviewId) {
        const button = document.querySelector(`[data-review-id="${reviewId}"]`);
        const countSpan = button.querySelector('.helpful-count');
        
        // Disable button and show loading state
        button.disabled = true;
        button.classList.add('opacity-50', 'cursor-not-allowed');
        
        fetch(`/reviews/${reviewId}/helpful`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update the helpful count
                countSpan.textContent = data.helpful_count;
                
                // Change button appearance to indicate it was marked helpful
                button.classList.remove('text-[#9aa6c7]');
                button.classList.add('text-[#00d4ff]');
                
                // Show success message (optional)
                if (data.message) {
                    console.log(data.message);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Re-enable button on error
            button.disabled = false;
            button.classList.remove('opacity-50', 'cursor-not-allowed');
        });
    }

    // Delete a review (librarian only)
    function deleteReview(reviewId) {
        // Ask for confirmation
        if (!confirm('Are you sure you want to delete this review? This action cannot be undone.')) {
            return;
        }

        const reviewElement = document.getElementById(`review-${reviewId}`);
        
        fetch(`/reviews/${reviewId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to delete review');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Remove the review element with fade-out animation
                reviewElement.style.transition = 'opacity 0.3s ease-out';
                reviewElement.style.opacity = '0';
                
                // Remove element after animation
                setTimeout(() => {
                    reviewElement.remove();
                    
                    // Show success message
                    console.log(data.message);
                    
                    // If no reviews left, reload the page to show "No reviews" message
                    const reviewsContainer = document.querySelector('[class*="space-y-8"]');
                    if (reviewsContainer && reviewsContainer.children.length === 0) {
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    }
                }, 300);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to delete the review. Please try again.');
        });
    }
</script>

@endsection
