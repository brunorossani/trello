<?php

namespace App\Http\Requests\Cards;

use App\Models\Card;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('card'));
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => trim($this->title),
        ]);
    }

    public function rules(): array
    {
        /** @var Card $card */
        $card = $this->route('card');

        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'member_ids' => ['array'],

            'member_ids.*' => [
                'integer',
                Rule::exists('team_members', 'user_id')
                    ->where('team_id', $card->list->board->team_id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The card title is required.',
        ];
    }
}
