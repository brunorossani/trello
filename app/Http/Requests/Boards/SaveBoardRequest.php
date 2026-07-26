<?php

namespace App\Http\Requests\Boards;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use App\Models\Board;

class SaveBoardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create', Board::class);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim($this->name),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',

                Rule::unique('boards')
                    ->where(function ($query) {
                        $query->where('team_id', $this->user()->current_team_id)
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