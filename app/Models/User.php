<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'is_verified',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function user_tokens() : HasMany
    {
        return $this->hasMany(UserToken::class);
    }

    public function user_request_logs() : HasMany
    {
        return $this->hasMany(UserRequestLog::class);
    }

    public function createToken($expiresAt = null)
    {
        $expiresAt ??= now()->addHours(2);
        $plainTextToken = sprintf(
            '%s%s%s',
            '',
            $tokenEntropy = Str::random(40),
            hash('crc32b', $tokenEntropy)
        );
        return $this->user_tokens()->create([
            'access_token' => $plainTextToken,
            'expires_at' => $expiresAt,
        ]);
    }

}
