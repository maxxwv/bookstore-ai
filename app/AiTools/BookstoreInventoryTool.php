<?php

namespace App\AiTools;

use App\Models\Book;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Facades\Tool;
use Prism\Prism\Tool as BaseTool;

class BookstoreInventoryTool
{
	public function setUpTool(): BaseTool
	{
		return Tool::as('inventory')
			->for('Looking up book availability in the shop')
			->withStringParameter('title', 'Title of the book')
			->using(function($title){
				return $this->handle($title);
			});
	}

	private function handle(string $title): string
	{
		$inv = Book::query()
			->where('title', $title)
			->get();
		return $inv->toJson();
	}
}