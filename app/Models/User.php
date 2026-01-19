<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Borrow;
use App\Models\Fine;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'user_type',
        'membership',
        'library_card_id',
        'registration_fee_paid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'membership' => 'boolean',
    ];

    /**
     * Get the reviews for this user.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get borrows for this user.
     */
    public function borrows(): HasMany
    {
        return $this->hasMany(Borrow::class);
    }

    /**
     * Get fines for this user.
     */
    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class);
    }

    /**
     * Get unpaid fines for this user.
     */
    public function unpaidFines(): HasMany
    {
        return $this->fines()->whereIn('status', ['unpaid', 'partial']);
    }

    /**
     * Calculate total outstanding fines.
     */
    public function getTotalOutstandingFines(): float
    {
        return (float) $this->unpaidFines()->sum(\Illuminate\Database\Query\Expression::raw('(amount - amount_paid)'));
    }

    /**
     * Get membership status details.
     */
    public function getMembershipStatus(): array
    {
        return [
            'is_member' => (bool) $this->membership,
            'registration_fee_paid' => (bool) $this->registration_fee_paid,
            'library_card_id' => $this->library_card_id,
            'active' => (bool) $this->membership && (bool) $this->registration_fee_paid,
        ];
    }
}
