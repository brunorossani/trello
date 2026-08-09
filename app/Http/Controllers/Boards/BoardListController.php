<?php

namespace App\Http\Controllers\Boards;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lists\SaveBoardListRequest;
use App\Http\Requests\Lists\UpdateBoardListRequest;
use App\Models\Board;
use App\Models\BoardList;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class BoardListController extends Controller
{
    public function store(SaveBoardListRequest $request, string $current_team, Board $board): RedirectResponse
    {
        $board->lists()->create([
            'name' => $request->validated('name'),
        ]);

        return back();
    }

    public function update(UpdateBoardListRequest $request, string $current_team, Board $board, BoardList $list): RedirectResponse
    {
        $list->update([
            'name' => $request->validated('name'),
        ]);

        return back();
    }

    public function destroy(string $current_team, Board $board, BoardList $list): RedirectResponse
    {
        Gate::authorize('delete', $list);

        $list->delete();

        return back();
    }
}
