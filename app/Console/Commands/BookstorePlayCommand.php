<?php

namespace App\Console\Commands;

use App\Enums\AiModelEnum;
use App\Http\Controllers\AiConversationController;
use App\Http\Requests\AiConversationRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\spin;
use function Laravel\Prompts\text;

class BookstorePlayCommand extends Command
{
	private string $conversationId = '';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'explore:bookstore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function handle(): int
    {
        while(true) {
			$text = text('Text');
			if(in_array($text, ['stop', 'end', 'e', 'quit', 'exit'])) {
				$this->info('Finishing');
				break;
			}
			$this->processConversation($text);
		}
		return self::SUCCESS;
    }

	private function processConversation(string $text): void
	{
		$request = new AiConversationRequest(
			[
				'conversation_id' => $this->conversationId ?? null,
				'message_content' => $text,
				'model' => AiModelEnum::Gpt4oMini->value
			]
		);

		$response = spin(
			message: 'Communicating',
			callback: function() use ($request) {
				return new AiConversationController()->ingest($request);
			}
		);

		$resp = $response->getOriginalContent();

		$this->conversationId = $resp['conversation_id'] ?? $this->conversationId;
		$this->line($resp['message_content']);
		$this->line('(prompt tokens: ' . $resp['promptTokens'] . ') (completion tokens: ' . $resp['completionTokens'] . ')');
		$this->newLine();
	}
}
