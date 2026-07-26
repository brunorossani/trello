<?php

namespace App\Http\Controllers\Boards;

use App\Http\Requests\Lists\SaveBoardListRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Board;

class BoardListController extends Controller
{
    public function store(SaveBoardListRequest $request, string $current_team, Board $board)
    {
        $board->lists()->create([
            'name' => $request->validated('name'),
        ]);

        return back();
    }
}
