<?php

namespace App\Http\Requests;

use App\Enums\AiMessageTypeEnum;
use App\Enums\AiModelEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AiConversationRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'conversation_id' => 'sometimes|required|uuid',
			'model' => [
				'required',
				Rule::in(array_column(AiModelEnum::cases(), 'value')),
			],
			'message_content' => 'required',
        ];
    }

	public function isNewConversation(): bool
	{
		return empty($this->conversation_id);
	}
}
