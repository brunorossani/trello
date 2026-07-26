<?php

namespace App\Http\Requests\Boards;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use App\Models\Board;

class UpdateBoardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('board'));
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim($this->name),
        ]);
    }

    public function rules(): array
    {
        $board = $this->route('board');

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',

                Rule::unique('boards')
                    ->ignore($board->id)
                    ->where(function ($query) use ($board) {
                        $query->where('team_id', $board->team_id)
                            ->whereNull('deleted_at');
                    }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The board name is required.',
            'name.min' => 'The board name must have at least 3 characters.',
            'name.unique' => 'You already have a board with this name in this team.',
        ];
    }
}