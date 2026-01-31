<?php

namespace App\Repositories;

use App\Enums\AiMessageTypeEnum;
use App\Enums\AiModelEnum;
use App\Http\Requests\AiConversationRequest;
use App\Models\AiConversation;
use Illuminate\Support\Facades\Log;

class AiConversationRepository {
	public function createConversation(AiConversationRequest $request): string
	{
		$conversation = AiConversation::create([
			'model' => AiModelEnum::from($request->model),
		]);
		new AiMessageRepository()->createMessage(
			$conversation->conversation_id,
			$request->message_content,
			AiMessageTypeEnum::human
		);
		return $conversation->conversation_id;
	}

	public function retrieveConversation(string $conversationId): AiConversation
	{
		return AiConversation::with('messages')
			->where('conversation_id', $conversationId)
			->firstOrFail();
	}
}