<?php

namespace App\Http\Controllers;

use App\Enums\AiMessageTypeEnum;
use App\Http\Requests\AiConversationRequest;
use App\Models\AiConversation;
use App\Repositories\AiConversationRepository;
use App\Repositories\AiMessageRepository;
use App\Services\AiMessageService;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Exceptions\PrismRateLimitedException;
use Prism\Prism\ValueObjects\ProviderRateLimit;

class AiConversationController extends Controller
{
    public function getConversation(Request $request, bool $withMessages=true): JsonResponse
	{
		$conversation = AiConversation::where('conversation_id', $request->conversation_id)
			->when($withMessages, function($query){
				$query->with('messages', function($query){
					$query->orderBy('created_at');
				});
			})
			->first();
		return response()->json($conversation);
	}

	public function ingest(AiConversationRequest $request): Response | ResponseFactory
	{
		try {
			if($request->isNewConversation()) {
				$conversationId = new AiConversationRepository()->createConversation($request);
			} else {
				new AiMessageRepository()->createMessage(
					$request->conversation_id,
					$request->message_content,
					AiMessageTypeEnum::human
				);
				$conversationId = $request->conversation_id;
			}
			$response = new AiMessageService()->converse($conversationId);
			return response([
				'conversation_id' => $conversationId,
				'message_content' => $response->text,
				'promptTokens' => $response->usage->promptTokens,
				'completionTokens' => $response->usage->completionTokens,
			]);
		} catch(PrismRateLimitedException $e) {
			$rateLimit = Arr::first($e->rateLimits, function(ProviderRateLimit $limit) {
				return $limit->remaining === 0;
			});
			return response([
				'message_content' => 'Rate limit exceeded: ' . $rateLimit,
				'Rate limit exceeded: ' . $rateLimit,
			], Response::HTTP_PAYMENT_REQUIRED);
		} catch(Exception $e) {
			Log::error("Exception {$e->getCode()} {$e->getMessage()}");
			return response([
				'Something went wrong.',
			], Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
}
