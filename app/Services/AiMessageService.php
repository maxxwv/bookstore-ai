<?php

namespace App\Services;

use App\AiTools\BookstoreInventoryTool;
use App\Enums\AiMessageTypeEnum;
use App\Repositories\AiConversationRepository;
use App\Repositories\AiMessageRepository;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Text\Response;
use Prism\Prism\Enums\ToolChoice;

class AiMessageService {
	private string|null $aiProvider;
	private string|null $aiModel;

	/**
	 * @var \Prism\Prism\Tool
	 */
	private $inventoryTool;

	public function converse(string $conversationId): Response
	{
		$conversation = new AiConversationRepository()->retrieveConversation($conversationId);
		$this->inventoryTool = new BookstoreInventoryTool()->setUpTool();
		$this->aiProvider = config('prism.ai_provider');
		$this->aiModel = config('prism.ai_model');

		$response = Prism::text()
			->using($this->aiProvider, $this->aiModel)
			->usingTemperature(0.3)
			->withClientOptions([
				'timeout' => 75,
			])
			// ->withSystemPrompt(view('prompts.bookstore.base'))
			// ->withSystemPrompt(view('prompts.bookstore.inventory', [
			->withSystemPrompt(view('prompts.bookstore.full', [
					'store_data' => 'Store information:
						- Name: My Awesome Bookstore
						- Address: 123 Here St. Wilmingtest NC, 28401
						- Business Hours:
							Sunday: Closed, Monday: 7:00am - 6:00pm; Tuesday: 8:00am - 6:00pm; Wednesday: 7:00am - 5:00pm; Thursday: 7:00am - 7:00pm; Friday: 8:00am - 5:00pm; Saturday: 9:00am - 4:00pm'
			]))
			->withMessages($conversation->messages->pluck('message_content')->all())
			->withTools([
				$this->inventoryTool
			])
			->withToolChoice(ToolChoice::Auto)
			->withMaxSteps(2)
			->asText();
		new AiMessageRepository()->createMessage(
			$conversationId,
			$response->text,
			AiMessageTypeEnum::ai
		);
		return $response;

	}
}