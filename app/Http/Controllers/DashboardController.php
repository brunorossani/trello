<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Team;
use App\Models\TeamInvitation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $email = strtolower($request->user()->email);

        $pendingInvitations = TeamInvitation::query()
            ->with(['inviter', 'team'])
            ->whereRaw('LOWER(email) = ?', [$email])
            ->whereNull('accepted_at')
            ->where(fn ($query) => $query
                ->whereNull('expires_at')
                ->orWhere('expires_at', '>=', now()))
            ->latest()
            ->get()
            ->map(fn (TeamInvitation $invitation) => [
                'code' => $invitation->code,
                'inviterName' => $invitation->inviter->name,
                'team' => [
                    'name' => $invitation->team->name,
                    'slug' => $invitation->team->slug,
                ],
            ]);

        $currentTeam = $request->route('current_team');

        if (is_string($currentTeam)) {
            $currentTeam = Team::where('slug', $currentTeam)->first();
        }

        $currentTeam = $currentTeam ?? $request->user()->currentTeam;

        $boards = collect();

        if ($currentTeam) {
            $boards = Board::query()
                ->where('team_id', $currentTeam->id)
                ->with(['lists.cards.members'])
                ->orderBy('created_at')
                ->get()
                ->map(function (Board $board) {
                    return [
                        'id' => $board->id,
                        'name' => $board->name,
                        'slug' => $board->slug,
                        'isPrivate' => $board->is_private,
                        'lists' => $board->lists->map(function ($list) {
                            return [
                                'id' => $list->id,
                                'name' => $list->name,
                                'position' => $list->position,
                                'cards' => $list->cards->sortBy('position')->map(function ($card) {
                                    return [
                                        'id' => $card->id,
                                        'title' => $card->title,
                                        'description' => $card->description,
                                        'position' => $card->position,
                                        'dueAt' => $card->due_at?->toDateString(),
                                        'isArchived' => $card->is_archived,
                                        'members' => $card->members->map(fn ($user) => [
                                            'id' => $user->id,
                                            'name' => $user->name,
                                        ])->values(),
                                    ];
                                })->values(),
                            ];
                        })->values(),
                    ];
                });
        }

        return Inertia::render('Dashboard', [
            'pendingInvitations' => $pendingInvitations,
            'boards' => $boards,
        ]);
    }
}
