<?php

namespace App\Actions\BoardLists;

use App\Models\BoardList;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateBoardList
{
    /**
     * Create a new BoardList and add the user as owner.
     */
    public function handle(User $user, string $name): BoardList
    {
        return DB::transaction(function () use ($user, $name) {
            $boardList = BoardList::create([
                'board_id' => $board_id,
                'name' => $name,
                'position' => 0,
                'team_id' => $user->current_team_id,
            ]);

            return $boardList;
        });
    }
}
