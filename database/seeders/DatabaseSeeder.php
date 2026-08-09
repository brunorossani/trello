<?php

namespace Database\Seeders;

use App\Enums\TeamRole;
use App\Models\Board;
use App\Models\BoardList;
use App\Models\Card;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class DatabaseSeeder extends Seeder
{
    private const LIST_NAMES = ['Backlog', 'To Do', 'In Progress', 'In Review', 'Done'];

    private const BOARDS = [
        ['name' => 'Product Roadmap', 'is_private' => false],
        ['name' => 'Marketing Sprint', 'is_private' => true],
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $owner = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $teammates = User::factory(5)->create();

        $team = Team::create(['name' => 'Acme Product Team', 'is_personal' => false]);

        $team->members()->attach($owner->id, ['role' => TeamRole::Owner->value]);
        $team->members()->attach($teammates[0]->id, ['role' => TeamRole::Admin->value]);
        $team->members()->attach($teammates->slice(1)->pluck('id'), ['role' => TeamRole::Member->value]);

        $owner->switchTeam($team);

        $members = $teammates->push($owner);

        foreach (self::BOARDS as $boardData) {
            $board = Board::create([
                'team_id' => $team->id,
                'owner_id' => $owner->id,
                'name' => $boardData['name'],
                'is_private' => $boardData['is_private'],
            ]);

            $this->seedLists($board, $members);
        }
    }

    /**
     * @param  Collection<int, User>  $members
     */
    private function seedLists(Board $board, $members): void
    {
        foreach (self::LIST_NAMES as $position => $name) {
            $list = BoardList::create([
                'board_id' => $board->id,
                'name' => $name,
                'position' => $position,
            ]);

            $this->seedCards($list, $members);
        }
    }

    /**
     * @param  Collection<int, User>  $members
     */
    private function seedCards(BoardList $list, $members): void
    {
        $cardCount = fake()->numberBetween(2, 6);

        for ($position = 0; $position < $cardCount; $position++) {
            $card = Card::create([
                'board_list_id' => $list->id,
                'title' => fake()->sentence(fake()->numberBetween(2, 6)),
                'description' => fake()->boolean(60) ? fake()->paragraph() : null,
                'position' => $position,
                'due_at' => fake()->boolean(35) ? fake()->dateTimeBetween('now', '+3 weeks') : null,
                'is_archived' => false,
            ]);

            $assigneeCount = fake()->numberBetween(0, min(3, $members->count()));

            if ($assigneeCount > 0) {
                $card->members()->attach($members->random($assigneeCount)->pluck('id'));
            }
        }
    }
}
