<?php

namespace App\Http\Requests\Lists;

use App\Models\BoardList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateBoardListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('list'));
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim($this->name),
        ]);
    }

    public function rules(): array
    {
        /** @var BoardList $list */
        $list = $this->route('list');

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',

                Rule::unique('board_lists')
                    ->where(function ($query) use ($list) {
                        $query->where('board_id', $list->board_id);
                    })
                    ->ignore($list->id),
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
