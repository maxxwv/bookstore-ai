<?php

namespace App\Repositories;

use App\Enums\AiMessageTypeEnum;
use App\Models\AiMessage;

class AiMessageRepository {
	public function createMessage(string $conversation_id, string $message_content, AiMessageTypeEnum $message_type): void
	{
		AiMessage::create([
			'conversation_id' => $conversation_id,
			'message_content' => $message_content,
			'message_type' => $message_type->value,
		]);
	}
}