<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property int $id
 * @property int $board_id
 * @property int $board_list_id
 * @property string $title
 * @property string|null $description
 * @property int $position
 * @property Carbon|null $due_at
 * @property bool $is_archived
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Board $board
 * @property-read BoardList $list
 * @property-read Collection<int, User> $members
 */
#[Fillable(['board_id', 'board_list_id', 'title', 'description', 'position', 'due_at', 'is_archived'])]
class Card extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_archived' => 'boolean',
            'due_at' => 'datetime',
        ];
    }

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    public function list(): BelongsTo
    {
        return $this->belongsTo(BoardList::class, 'board_list_id');
    }

    /**
     * Members assigned to this card.
     *
     * @return BelongsToMany<User, $this, CardMember, 'pivot'>
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'card_members', 'card_id', 'user_id')
            ->using(CardMember::class)
            ->withTimestamps();
    }
}
