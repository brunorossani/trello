<?php

namespace App\Http\Controllers\Boards;

use App\Actions\Cards\CreateCard;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cards\SaveCardRequest;
use App\Http\Requests\Cards\UpdateCardRequest;
use App\Models\Board;
use App\Models\BoardList;
use App\Models\Card;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class CardController extends Controller
{
    public function store(SaveCardRequest $request, string $current_team, Board $board, BoardList $list, CreateCard $action): RedirectResponse
    {
        $action->handle(
            $list,
            $request->validated('title'),
            $request->validated('description'),
            $request->validated('member_ids') ?? [],
        );

        return back();
    }

    public function update(UpdateCardRequest $request, string $current_team, Board $board, Card $card): RedirectResponse
    {
        $card->update([
            'title' => $request->validated('title'),
            'description' => $request->validated('description'),
        ]);

        $card->members()->sync($request->validated('member_ids') ?? []);

        return back();
    }

    public function destroy(string $current_team, Board $board, Card $card): RedirectResponse
    {
        Gate::authorize('delete', $card);

        $card->delete();

        return back();
    }
}
