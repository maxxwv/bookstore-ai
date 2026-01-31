<?php

namespace Tests\Feature;

use App\Models\AiConversation;
use App\Models\AiMessage;
use Database\Seeders\BookSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Prism\Prism\Enums\FinishReason;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Testing\TextResponseFake;
use Prism\Prism\Testing\TextStepFake;
use Prism\Prism\Text\ResponseBuilder;
use Prism\Prism\ValueObjects\ToolCall;
use Prism\Prism\ValueObjects\ToolResult;
use Prism\Prism\ValueObjects\Usage;
use Tests\TestCase;

#[Group('ai-test')]
class AiConversationTest extends TestCase
{
	use RefreshDatabase;

	public function setUpBooksTable(): void
	{
		$this->seed(BookSeeder::class);
	}

    /**
     * Ensure we can create a new conversation.
     */
    public function test_can_create_conversation(): void
    {
		$text = 'Hello, Alex! How can I help you today!';
		$fakeResponse = TextResponseFake::make()
			->withText($text)
			->withUsage(new Usage(10, 20));
		Prism::fake([$fakeResponse]);

		$current = AiConversation::count();
		$this->assertEquals(0, $current);
		$response = $this->post('/ai/conversation', [
			'message_content' => 'Hello. My name is Alex',
			'model' => 0,
		]);

		$this->assertEquals($text, $response['message_content']);
		$this->assertEquals(10, $response['promptTokens']);
		$this->assertEquals(20, $response['completionTokens']);
    }

	public function test_can_add_to_conversation(): void
	{
		$respText1 = "Hello Alex! How can I help you today?";
		$fakeResp1 = TextResponseFake::make()
			->withText($respText1)
			->withUsage(new Usage(10,20));

		$respText2 = "It's one of the best books ever written, in my humble opinion.";
		$fakeResp2 = TextResponseFake::make()
			->withText($respText2)
			->withUsage(new Usage(5, 15));

		Prism::fake([$fakeResp1, $fakeResp2]);

		$currentConversations = AiConversation::count();
		$this->assertEquals(0, $currentConversations);

		$response = $this->post('/ai/conversation', [
			'message_content' => 'Hi. My name is Alex',
			'model' => 0,
		]);
		$this->assertEquals($respText1, $response['message_content']);

		$response = $this->post('/ai/conversation', [
			'message_content' => 'Tell me about the book Neuromancer, please.',
			'conversation_id' => $response['conversation_id'],
			'model' => 0,
		]);

		$this->assertEquals($respText2, $response['message_content']);

		$this->assertEquals(1, AiConversation::count());
		$this->assertEquals(4, AiMessage::count());
	}

	public function test_can_check_inventory(): void
	{
		$fakeResponses = [
			new ResponseBuilder()
				->addStep(
					TextStepFake::make()
						->withToolCalls([
							new ToolCall(
								'call_1',
								'inventory',
								['title' => 'Neuromancer']
							),
						])
						->withFinishReason(FinishReason::ToolCalls)
						->withUsage(new Usage(15, 12))
				)
				->addStep(
					TextStepFake::make()
						->withText('12')
						->withToolResults([
							new ToolResult(
								'call_1',
								'inventory',
								['title' => 'Neuromancer'],
								'12'
							),
						])
						->withFinishReason(FinishReason::Stop)
						->withUsage(new Usage(5, 15))
				)
				->toResponse()
		];

		Prism::fake($fakeResponses);

		$response = $this->post('/ai/conversation', [
			'message_content' => 'How many copies of Neuromancer do you have in stock?',
			'model' => 0,
		]);

		$this->assertEquals(12, $response['message_content']);
	}
}
