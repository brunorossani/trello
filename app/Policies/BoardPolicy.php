<?php

namespace App\Policies;

use App\Enums\TeamPermission;
use App\Models\Board;
use App\Models\User;

class BoardPolicy
{
    public function create(User $user): bool
    {
        return $user->currentTeam !== null
            && $user->hasTeamPermission(
                $user->currentTeam,
                TeamPermission::CreateBoard,
            );
    }

    public function update(User $user, Board $board): bool
    {
        return $user->belongsToTeam($board->team)
            && $user->hasTeamPermission(
                $board->team,
                TeamPermission::UpdateBoard,
            );
    }

    public function delete(User $user, Board $board): bool
    {
        return $user->belongsToTeam($board->team)
            && $user->hasTeamPermission(
                $board->team,
                TeamPermission::DeleteBoard,
            );
    }
}