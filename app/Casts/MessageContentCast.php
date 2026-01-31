<?php

namespace App\Casts;

use App\Enums\AiMessageTypeEnum;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Contracts\Message;
use Prism\Prism\ValueObjects\Messages\SystemMessage;
use Prism\Prism\ValueObjects\Messages\AssistantMessage;
use Prism\Prism\ValueObjects\Messages\ToolResultMessage;
use Prism\Prism\ValueObjects\Messages\UserMessage;

/**
 * @implements CastsAttributes<SystemMessage|AssistantMessage|ToolResultMessage|UserMessage, Message>
 */
class MessageContentCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
	 * @param \App\Models\AiMessage $model
     * @param array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
		if($key == 'message_content') {
			$messageType = "Prism\\Prism\\ValueObjects\\Messages\\" . AiMessageTypeEnum::from($model->message_type)->messageType();
			$value = new $messageType($value);
		}
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }
}
