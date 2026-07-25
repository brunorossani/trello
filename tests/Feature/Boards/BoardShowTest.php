<?php

use App\Models\Board;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('authenticated users can visit a board show page', function () {
    $user = User::factory()->create();
    $team = $user->currentTeam;
    $board = Board::factory()->create([
        'team_id' => $team->id,
        'name' => 'Sprint Board',
        'slug' => 'sprint-board',
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('boards.show', [
            'current_team' => $team->slug,
            'board' => $board->slug,
        ]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('boards/Show')
        ->has('board', fn (Assert $boardPage) => $boardPage
            ->where('slug', $board->slug)
            ->where('name', 'Sprint Board')
        ),
    );
});
