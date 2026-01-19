<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Borrow;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'description',
        'isbn',
        'pages',
        'publisher',
        'published_date',
        'cover_image',
        'price',
        'rating',
        'rating_count',
        'view_count',
        'recommended_count',
    ];

    protected $casts = [
        'published_date' => 'date',
        'rating' => 'float',
    ];

    /**
     * Get the genres for the book.
     */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'book_genre');
    }

    /**
     * Get the reviews for the book.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get borrows for the book.
     */
    public function borrows(): HasMany
    {
        return $this->hasMany(Borrow::class);
    }

    /**
     * Get trending books (by view count).
     */
    public static function trending(int $limit = 8)
    {
        return self::orderBy('view_count', 'desc')
                    ->limit($limit)
                    ->get();
    }

    /**
     * Get recommended books (by rating and view count).
     */
    public static function recommended(int $limit = 8)
    {
        return self::where('rating_count', '>', 0)
                    ->orderByRaw('(rating * rating_count) DESC')
                    ->limit($limit)
                    ->get();
    }

    /**
     * Increment view count.
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    /**
     * Scope a query to search books by a free-text term (title, author, description).
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);
        if ($term === '') {
            return $query;
        }

        $like = '%' . str_replace(' ', '%', $term) . '%';

        return $query->where(function ($q) use ($like) {
            $q->where('title', 'like', $like)
              ->orWhere('author', 'like', $like);

            if (Schema::hasColumn('books', 'description')) {
                $q->orWhere('description', 'like', $like);
            }
        });
    }
}
