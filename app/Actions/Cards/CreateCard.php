<?php

namespace App\Actions\Cards;

use App\Models\BoardList;
use App\Models\Card;
use Illuminate\Support\Facades\DB;

class CreateCard
{
    /**
     * Create a new card at the end of the given list.
     *
     * @param  array<int>  $memberIds
     */
    public function handle(BoardList $list, string $title, ?string $description = null, array $memberIds = []): Card
    {
        return DB::transaction(function () use ($list, $title, $description, $memberIds) {
            $position = ($list->cards()->max('position') ?? -1) + 1;

            $card = Card::create([
                'board_list_id' => $list->id,
                'title' => $title,
                'description' => $description,
                'position' => $position,
            ]);

            $card->members()->sync($memberIds);

            return $card;
        });
    }
}
