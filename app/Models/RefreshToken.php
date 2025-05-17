<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SensitiveParameter;

/**
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property string|null $user_agent
 * @property string|null $ip_address
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $user
 *
 * @method static Builder<RefreshToken> byToken(string $token)
 */
class RefreshToken extends Model
{
    use MassPrunable;

    protected $fillable = [
        'token',
        'user_agent',
        'ip_address',
        'expires_at',
    ];

    protected $hidden = [
        'token',
    ];

    public function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    /**
     * @return Builder<static>
     */
    public function prunable(): Builder
    {
        return static::query()->where('expires_at', '<=', now());
    }

    /**
     * @param  Builder<$this>  $query
     */
    #[Scope]
    protected function byToken(Builder $query, #[SensitiveParameter] string $token): void
    {
        $query->where('token', hash('sha256', $token));
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
