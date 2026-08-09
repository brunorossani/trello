<?php

namespace App\Policies;

use App\Enums\TeamPermission;
use App\Models\Card;
use App\Models\User;

class CardPolicy
{
    public function create(User $user): bool
    {
        return $user->currentTeam !== null
            && $user->hasTeamPermission(
                $user->currentTeam,
                TeamPermission::CreateCard,
            );
    }

    public function update(User $user, Card $card): bool
    {
        return $user->belongsToTeam($card->list->board->team)
            && $user->hasTeamPermission(
                $card->list->board->team,
                TeamPermission::UpdateCard,
            );
    }

    public function delete(User $user, Card $card): bool
    {
        return $user->belongsToTeam($card->list->board->team)
            && $user->hasTeamPermission(
                $card->list->board->team,
                TeamPermission::DeleteCard,
            );
    }
}
