<?php

namespace App\Http\Requests\Lists;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use App\Models\BoardList;

class SaveBoardListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create', BoardList::class);
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

                Rule::unique('board_lists')
                    ->where(function ($query) use ($board) {
                        $query->where('board_id', $board->id);
                    }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The list name is required.',
            'name.min' => 'The list name must have at least 3 characters.',
            'name.unique' => 'You already have a list with this name in this board.',
        ];
    }
}