<?php

namespace App\Policies;

use App\Enums\TeamPermission;
use App\Models\BoardList;
use App\Models\User;

class BoardListPolicy
{
    public function create(User $user): bool
    {
        return $user->currentTeam !== null
            && $user->hasTeamPermission(
                $user->currentTeam,
                TeamPermission::CreateBoardList,
            );
    }

    public function update(User $user, BoardList $list): bool
    {
        return $user->belongsToTeam($list->board->team)
            && $user->hasTeamPermission(
                $list->board->team,
                TeamPermission::UpdateBoardList,
            );
    }

    public function delete(User $user, BoardList $list): bool
    {
        return $user->belongsToTeam($list->board->team)
            && $user->hasTeamPermission(
                $list->board->team,
                TeamPermission::DeleteBoardList,
            );
    }
}