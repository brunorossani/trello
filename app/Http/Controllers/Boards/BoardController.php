<?php

namespace App\Http\Controllers\Boards;

use App\Actions\Boards\CreateBoard;
use App\Enums\TeamPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Boards\SaveBoardRequest;
use App\Models\Board;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BoardController extends Controller
{
    public function store(SaveBoardRequest $request, CreateBoard $action): RedirectResponse
    {
        $action->handle($request->user(), $request->validated('name'));

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Board created successfully.')]);

        return to_route('dashboard', [
            'current_team' => $request->user()->currentTeam?->slug,
        ]);
    }

    public function show(Request $request, string $currentTeam, Board $board): Response
    {
        $board->load(['lists.cards.members', 'team.members']);

        $user = $request->user();

        return Inertia::render('boards/Show', [
            'teamMembers' => $board->team->members->map(fn ($member) => [
                'id' => $member->id,
                'name' => $member->name,
            ])->values(),
            'permissions' => [
                'canUpdateCard' => $user->hasTeamPermission($board->team, TeamPermission::UpdateCard),
                'canDeleteCard' => $user->hasTeamPermission($board->team, TeamPermission::DeleteCard),
                'canUpdateBoardList' => $user->hasTeamPermission($board->team, TeamPermission::UpdateBoardList),
                'canDeleteBoardList' => $user->hasTeamPermission($board->team, TeamPermission::DeleteBoardList),
            ],
            'board' => [
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
                                'members' => $card->members->map(fn ($member) => [
                                    'id' => $member->id,
                                    'name' => $member->name,
                                ])->values(),
                            ];
                        })->values(),
                    ];
                })->values(),
            ],
        ]);
    }
}
