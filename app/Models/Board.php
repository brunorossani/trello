<?php

namespace App\Models;

use App\Concerns\GeneratesUniqueBoardSlugs;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property int $id
 * @property string $name
 * @property string|null $slug
 * @property int $team_id
 * @property int|null $owner_id
 * @property bool $is_private
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Team $team
 * @property-read Collection<int, BoardList> $lists
 * @property-read Collection<int, Card> $cards
 * @property-read User|null $owner
 */
#[Fillable(['team_id', 'name', 'slug', 'owner_id', 'is_private'])]
class Board extends Model
{
    use GeneratesUniqueBoardSlugs, HasFactory, SoftDeletes;

    /**
     * Bootstrap the model and its traits.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Board $board) {
            if (empty($board->slug)) {
                $board->slug = static::generateUniqueBoardSlug($board->name);
            }
        });

        static::updating(function (Board $board) {
            if ($board->isDirty('name')) {
                $board->slug = static::generateUniqueBoardSlug($board->name, $board->id);
            }
        });
    }


    /**
     * Get the team that owns the board.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the lists that belong to the board.
     *
     * @return HasMany<BoardList, $this>
     */
    public function lists(): HasMany
    {
        return $this->hasMany(BoardList::class)->orderBy('position');
    }

    /**
     * Get the cards that belong to the board.
     *
     * @return HasMany<Card, $this>
     */
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    /**
     * Get the owner of the board.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    protected function casts(): array
    {
        return [
            'is_private' => 'boolean',
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
