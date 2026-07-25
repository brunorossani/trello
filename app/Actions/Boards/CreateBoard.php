<?php

namespace App\Actions\Boards;

use App\Models\Board;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateBoard
{
    /**
     * Create a new board and add the user as owner.
     */
    public function handle(User $user, string $name, bool $isPrivate = false): Board
    {
        return DB::transaction(function () use ($user, $name, $isPrivate) {
            $board = Board::create([
                'name' => $name,
                'team_id' => $user->current_team_id,
                'owner_id' => $user->id,
                'is_private' => $isPrivate,
            ]);

            return $board;
        });
    }
}
